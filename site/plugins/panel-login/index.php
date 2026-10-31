<?php

use Kirby\Cms\App;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Toolkit\Str;

/**
 * Accesso al Panel con il nome utente oppure con l'email.
 *
 * Il form di login di Kirby accetta solo un'email, e la rotta auth/login
 * cerca l'utente solo per email. Qui:
 *  - index.js sostituisce il campo "Email" con "Utente o email";
 *  - se chi scrive NON mette una @, il form chiama la rotta qui sotto, che
 *    trasforma il nome nell'email corrispondente e poi fa il login di Kirby
 *    di sempre (stesso controllo CSRF, stesso limite di tentativi, stesso
 *    messaggio d'errore generico: non si capisce se il nome esiste);
 *  - con la @ il form usa direttamente la rotta di Kirby, come prima.
 *
 * "Utente" è il Nome dell'account (Utenti → modifica → Nome), senza
 * distinguere maiuscole e spazi doppi. Se due account hanno lo stesso nome
 * non si sceglie a caso: quel nome non funziona e si entra con l'email.
 */
final class PanelLogin
{
  private static function normalize(string $name): string
  {
    return Str::lower(trim(preg_replace('!\s+!u', ' ', $name)));
  }

  /** Email dell'unico account con questo nome, altrimenti il testo com'è. */
  public static function resolve(App $kirby, string $identifier): string
  {
    $identifier = trim($identifier);
    $wanted     = self::normalize($identifier);

    if ($wanted === '' || str_contains($identifier, '@')) {
      return $identifier;
    }

    $matches = $kirby->users()->filter(
      fn ($user) => self::normalize((string) $user->name()->value()) === $wanted
    );

    return $matches->count() === 1 ? (string) $matches->first()->email() : $identifier;
  }

  /** Esiste già un account con questo nome? (vedi panel-registration) */
  public static function nameTaken(App $kirby, string $name): bool
  {
    $wanted = self::normalize($name);

    return $wanted !== '' && $kirby->users()->filter(
      fn ($user) => self::normalize((string) $user->name()->value()) === $wanted
    )->count() > 0;
  }
}

Kirby::plugin('consulta/panel-login', [
  'api' => [
    'routes' => [
      [
        'pattern' => 'login-by-name',
        'method'  => 'POST',
        'auth'    => false,
        'action'  => function () {
          $kirby = $this->kirby();
          $auth  = $kirby->auth();

          // Stesso controllo CSRF di auth/login (kirby/config/api/routes/auth.php).
          if ($auth->type() === 'session' && $auth->csrf() === false) {
            throw new InvalidArgumentException(message: 'Invalid CSRF token');
          }

          $methods = $kirby->system()->loginMethods();
          if (isset($methods['password']) !== true || isset($methods['password']['2fa']) === true) {
            throw new InvalidArgumentException(message: 'Login with password is not enabled');
          }

          $password = $this->requestBody('password');
          if (!is_string($password) || $password === '') {
            throw new InvalidArgumentException(message: 'Login with password is not enabled');
          }

          $identifier = $this->requestBody('email');
          $email      = PanelLogin::resolve($kirby, is_string($identifier) ? $identifier : '');

          $user = $auth->login($email, $password, (bool) $this->requestBody('long'));

          return [
            'code'   => 200,
            'status' => 'ok',
            'user'   => $this->resolve($user)->view('auth')->toArray(),
          ];
        },
      ],
    ],
  ],
]);
