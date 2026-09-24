<?php

use Kirby\Cms\App;
use Kirby\Http\Response;
use Kirby\Http\Url;
use Kirby\Toolkit\V;

const CONTACT_FORM_MAX_WORDS  = 500;
const CONTACT_FORM_MAX_CHARS  = 10000;
const CONTACT_FORM_FALLBACK_TO = 'consfim@gmail.com';
// Mittente delle email: il dominio vero del sito (sul quale vanno configurati
// SPF/DKIM/DMARC), non quello da cui arriva la richiesta. Sostituibile con
// l'opzione 'consulta.contactForm.from' in site/config/config.php.
const CONTACT_FORM_FROM        = 'no-reply@consultafemminile.org';

/**
 * Testo mostrato all'utente per ciascun esito (chiave = "code" restituito
 * da contactFormProcess(), usata anche come ?contatto=... quando il form
 * viene inviato senza JavaScript — vedi bits/contact-form.php).
 */
function contactFormMessage(string $code): string
{
  return [
    'ok'       => 'Grazie, il tuo messaggio è stato inviato.',
    'sessione' => 'La sessione è scaduta: ricarica la pagina e riprova.',
    'dati'     => 'Controlla i campi: serve un\'email valida, un oggetto e un messaggio di al massimo ' . CONTACT_FORM_MAX_WORDS . ' parole.',
    'invio'    => 'Non è stato possibile inviare il messaggio. Riprova più tardi.',
  ][$code] ?? '';
}

/**
 * Valida un messaggio del form contatti e lo invia via email
 * all'indirizzo del sito (campo "contact_email" di site.yml).
 *
 * @return array{status: int, ok: bool, code: string, message: string}
 */
function contactFormProcess(App $kirby, array $input, bool $csrfValid): array
{
  $result = fn (int $status, string $code) => [
    'status'  => $status,
    'ok'      => $code === 'ok',
    'code'    => $code,
    'message' => contactFormMessage($code),
  ];

  if (!$csrfValid) {
    return $result(403, 'sessione');
  }

  // Trappola per i bot: un campo nascosto che un utente vero non vede né
  // compila. Se è pieno si finge comunque che sia andata bene, senza
  // inviare nulla, per non dare indizi a chi automatizza l'invio.
  if (contactFormField($input, 'sito_web') !== '') {
    return $result(200, 'ok');
  }

  // A capo e tabulazioni nell'oggetto/email sono la porta d'ingresso
  // dell'header injection: via, prima di qualunque altra cosa.
  $email     = preg_replace('/[\r\n\t]+/', ' ', contactFormField($input, 'email'));
  $oggetto   = preg_replace('/\s+/u', ' ', contactFormField($input, 'oggetto'));
  $messaggio = str_replace("\r\n", "\n", contactFormField($input, 'messaggio'));

  // Lunghezza prima delle parole: non ha senso spezzare in parole un
  // testo che è già troppo lungo.
  if (
    !V::email($email) ||
    $oggetto === '' || mb_strlen($oggetto) > 150 ||
    $messaggio === '' || mb_strlen($messaggio) > CONTACT_FORM_MAX_CHARS ||
    count(preg_split('/\s+/u', $messaggio, -1, PREG_SPLIT_NO_EMPTY)) > CONTACT_FORM_MAX_WORDS
  ) {
    return $result(422, 'dati');
  }

  $to = $kirby->site()->contact_email()->or(CONTACT_FORM_FALLBACK_TO)->value();

  try {
    $kirby->email([
      // Mittente fisso sul dominio dell'associazione, non ricavato dall'host
      // della richiesta: "www.consultafemminile.org" o un Host header
      // falsificato darebbero un mittente sbagliato (o scelto da chi
      // attacca). Non è nemmeno quello del visitante, che i server di posta
      // scarterebbero come spoofing: lui compare in Reply-To, così
      // "Rispondi" scrive a lui.
      'from'     => $kirby->option('consulta.contactForm.from', CONTACT_FORM_FROM),
      'fromName' => $kirby->site()->title()->value(),
      'replyTo'  => $email,
      'to'       => $to,
      'subject'  => 'Contatti dal sito: ' . $oggetto,
      'body'     => "Da: {$email}\nOggetto: {$oggetto}\n\n{$messaggio}\n",
    ]);
  } catch (Throwable $e) {
    // All'utente resta un messaggio generico, ma senza una traccia un
    // problema di posta farebbe perdere messaggi senza che nessuno lo sappia.
    error_log('contact-form: invio fallito — ' . $e->getMessage());

    return $result(500, 'invio');
  }

  return $result(200, 'ok');
}

/**
 * Valore di un campo del form come stringa, oppure '' se manca o non è
 * una stringa (es. "email[]=..." arriva come array e non va convertito).
 */
function contactFormField(array $input, string $key): string
{
  return is_string($input[$key] ?? null) ? trim($input[$key]) : '';
}

/**
 * Dove riportare l'utente dopo un invio senza JavaScript. Dal form si
 * prende solo il percorso: schema e host sono sempre quelli del sito, mai
 * quelli scritti nel campo. Confrontare l'inizio dell'URL non basterebbe
 * ("https://sito.it.evil.example" o "https://sito.it@evil.example"
 * iniziano entrambi con l'URL del sito ma portano altrove).
 */
function contactFormReturnUrl(App $kirby, string $ritorno, string $ancora, string $code): string
{
  $path = (string) parse_url($ritorno, PHP_URL_PATH);
  $path = '/' . ltrim(preg_replace('/[\x00-\x20\x7f]/', '', $path), '/\\');
  $hash = preg_replace('/[^A-Za-z0-9_-]/', '', $ancora);

  return Url::base($kirby->url()) . $path . '?contatto=' . $code . ($hash !== '' ? '#' . $hash : '');
}

Kirby::plugin('consulta/contact-form', [
  'routes' => [
    [
      'pattern' => 'invia-messaggio',
      'method'  => 'POST',
      'action'  => function () {
        $kirby  = kirby();
        $input  = $kirby->request()->body()->toArray();
        $result = contactFormProcess($kirby, $input, csrf(contactFormField($input, 'csrf')) === true);

        // Con JavaScript (bits/contact-form.js) la risposta è JSON e la
        // pagina resta dov'è; senza, si torna al form con l'esito in URL.
        if (str_contains((string) $kirby->request()->header('Accept'), 'application/json')) {
          return Response::json([
            'ok'      => $result['ok'],
            'message' => $result['message'],
          ], $result['status']);
        }

        return Response::redirect(
          contactFormReturnUrl($kirby, contactFormField($input, 'ritorno'), contactFormField($input, 'ancora'), $result['code']),
          303
        );
      },
    ],
  ],
]);
