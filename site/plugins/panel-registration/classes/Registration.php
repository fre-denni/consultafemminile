<?php

namespace Consulta\Registration;

use Kirby\Cms\App;
use Kirby\Cms\User;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Toolkit\V;

/**
 * Richiesta di accesso dalla pagina di login del Panel.
 *
 * Chi si registra ottiene un account col ruolo "in-attesa" (nessun
 * permesso, non può nemmeno entrare): un amministratore lo vede in Utenti,
 * gli assegna il ruolo che vuole e solo allora l'accesso si apre. Così la
 * gestione dei ruoli resta quella di sempre, in mano agli amministratori.
 *
 * Limiti di sicurezza (opzioni consulta.registration.*, vedi index.php):
 * tetto agli utenti totali e a quelli in attesa, tentativi per IP ogni
 * ora, campo trappola per i bot, CSRF come il login. Kirby Basic non ha
 * invece alcun limite sul numero di utenti: i tetti sono nostri.
 */
final class Registration
{
  public static function role(): string
  {
    return (string) option('consulta.registration.role', 'in-attesa');
  }

  public static function pendingCount(App $kirby): int
  {
    return $kirby->users()->filterBy('role', self::role())->count();
  }

  /** Il form è attivo e c'è ancora posto. */
  public static function isOpen(App $kirby): bool
  {
    if (!option('consulta.registration.active', true)) {
      return false;
    }

    return $kirby->users()->count() < (int) option('consulta.registration.maxUsers', 25)
      && self::pendingCount($kirby) < (int) option('consulta.registration.maxPending', 10);
  }

  /** Conta i tentativi dell'IP nell'ultima ora; false se ha superato il limite. */
  private static function throttle(App $kirby): bool
  {
    $cache = $kirby->cache('consulta.registration');

    if (!$cache->enabled()) {
      return true;
    }

    $key   = 'ip-' . hash('sha256', (string) $kirby->visitor()->ip() . '|' . $kirby->root('index'));
    $count = (int) $cache->get($key, 0);

    if ($count >= (int) option('consulta.registration.attempts', 5)) {
      return false;
    }

    $cache->set($key, $count + 1, 60); // minuti

    return true;
  }

  /**
   * @param array{name?: mixed, email?: mixed, password?: mixed, website?: mixed} $input
   * @throws InvalidArgumentException con un messaggio mostrabile all'utente
   */
  public static function register(App $kirby, array $input): void
  {
    // Campo trappola: i bot lo compilano, le persone non lo vedono.
    if (($input['website'] ?? '') !== '') {
      return; // finto successo: non si dà nulla da imparare ai bot
    }

    if (!self::isOpen($kirby)) {
      throw new InvalidArgumentException(message: 'Le richieste di accesso sono chiuse al momento. Contatta un amministratore.');
    }

    if (!self::throttle($kirby)) {
      throw new InvalidArgumentException(message: 'Troppi tentativi. Riprova tra un’ora.');
    }

    $name     = trim(strip_tags(is_string($input['name'] ?? null) ? $input['name'] : ''));
    $email    = mb_strtolower(trim(is_string($input['email'] ?? null) ? $input['email'] : ''));
    $password = is_string($input['password'] ?? null) ? $input['password'] : '';

    if (mb_strlen($name) < 2 || mb_strlen($name) > 80) {
      throw new InvalidArgumentException(message: 'Scrivi il tuo nome e cognome (da 2 a 80 caratteri).');
    }

    if (!V::email($email) || mb_strlen($email) > 190) {
      throw new InvalidArgumentException(message: 'Inserisci un indirizzo email valido.');
    }

    if (mb_strlen($password) < 8 || mb_strlen($password) > 200) {
      throw new InvalidArgumentException(message: 'La password deve avere almeno 8 caratteri.');
    }

    // Email già registrata: stessa risposta di un successo, per non far
    // scoprire a chiunque quali indirizzi hanno un account.
    if ($kirby->users()->findBy('email', $email)) {
      return;
    }

    $user = $kirby->impersonate('kirby', fn () => $kirby->users()->create([
      'email'    => $email,
      'name'     => $name,
      'password' => $password,
      'role'     => self::role(),
      'language' => 'it',
    ]));

    self::notifyAdmins($kirby, $user);
  }

  private static function notifyAdmins(App $kirby, User $user): void
  {
    if (!option('consulta.registration.notify', true)) {
      return;
    }

    $to = array_values(array_filter(
      $kirby->users()->filterBy('role', 'admin')->values(fn ($u) => (string) $u->email())
    ));

    if ($to === []) {
      $to = [defined('CONTACT_FORM_FALLBACK_TO') ? CONTACT_FORM_FALLBACK_TO : null];
      $to = array_filter($to);
    }

    if ($to === []) {
      return;
    }

    $from = $kirby->option('consulta.contactForm.from', defined('CONTACT_FORM_FROM') ? CONTACT_FORM_FROM : null);

    try {
      $kirby->email([
        'from'     => $from,
        'fromName' => $kirby->site()->title()->value(),
        'to'       => $to,
        'subject'  => 'Nuova richiesta di accesso al Panel: ' . $user->name(),
        'body'     => "Qualcuno ha chiesto l'accesso al Panel del sito.\n\n"
          . 'Nome: ' . $user->name() . "\n"
          . 'Email: ' . $user->email() . "\n\n"
          . "L'account è stato creato senza alcun permesso (ruolo \"" . self::role() . "\").\n"
          . "Se lo conosci, assegna un ruolo da Utenti → " . $user->name() . ' → ruolo; altrimenti eliminalo.' . "\n"
          . $kirby->url('panel') . '/users/' . $user->id() . "\n",
      ]);
    } catch (\Throwable $e) {
      // La richiesta resta comunque registrata e visibile in Utenti.
      error_log('panel-registration: invio notifica fallito — ' . $e->getMessage());
    }
  }
}
