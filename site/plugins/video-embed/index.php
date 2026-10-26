<?php

use Kirby\Http\Uri;
use Kirby\Toolkit\Str;

/**
 * Video da YouTube e Vimeo, solo tramite i loro indirizzi "senza cookie":
 *
 *   YouTube → www.youtube-nocookie.com/embed/ID  (modalità privacy avanzata)
 *   Vimeo   → player.vimeo.com/video/ID?dnt=1    (Do Not Track: niente
 *                                                 statistiche né cookie)
 *
 * Qualunque link accettato dal Panel (watch, youtu.be, shorts, embed, live,
 * link Vimeo anche con codice "non in elenco") viene ridotto a provider + ID,
 * e l'indirizzo di embed lo costruiamo noi: non si usa mai quello digitato
 * da chi scrive. Html::video() di Kirby non basta: i link youtu.be li
 * incorpora da youtube.com, con i cookie.
 *
 * Nessuna pubblicità: YouTube e Vimeo non la mostrano nell'embed solo se il
 * video non è monetizzato — non è un'opzione che si possa forzare da qui.
 * Il video si carica comunque solo quando si preme "Riproduci" (vedi
 * snippets/blocks/video.php), quindi finché non lo si avvia non parte
 * nessuna richiesta verso quei servizi.
 */
final class VideoEmbed
{
  /** @return array{provider:string,id:string,hash:?string,start:int}|null */
  public static function parse(?string $url): ?array
  {
    $url = trim((string)$url);
    if ($url === '') {
      return null;
    }

    // Accetta anche "youtube.com/watch?v=…" scritto senza schema.
    if (!preg_match('!^https?://!i', $url)) {
      $url = 'https://' . $url;
    }

    // Un indirizzo malformato fa fallire Uri: per noi è semplicemente "non valido".
    if (empty(parse_url($url, PHP_URL_HOST))) {
      return null;
    }

    try {
      $uri = new Uri($url);
    } catch (Throwable) {
      return null;
    }

    $host = Str::lower((string)$uri->host());
    $host = preg_replace('!^(www|m|music)\.!', '', $host);
    $path = $uri->path();
    $query = $uri->query();

    // ── YouTube ──
    if (in_array($host, ['youtube.com', 'youtube-nocookie.com', 'youtu.be'], true)) {
      $id = match (true) {
        $host === 'youtu.be'
          => $path->first(),
        $path->first() === 'watch'
          => $query->v,
        in_array($path->first(), ['embed', 'shorts', 'live', 'v'], true)
          => $path->nth(1),
        default => null,
      };

      if (!is_string($id) || !preg_match('!^[A-Za-z0-9_-]{6,20}$!', $id)) {
        return null;
      }

      // t=90, t=90s oppure t=1m30s
      $t = (string)($query->t ?? $query->start ?? '');
      $start = 0;
      if (preg_match('!^(?:(\d+)h)?(?:(\d+)m)?(?:(\d+)s?)?$!', $t, $m) && $t !== '') {
        $start = (int)($m[1] ?? 0) * 3600 + (int)($m[2] ?? 0) * 60 + (int)($m[3] ?? 0);
      }

      return ['provider' => 'youtube', 'id' => $id, 'hash' => null, 'start' => $start];
    }

    // ── Vimeo ──
    if (in_array($host, ['vimeo.com', 'player.vimeo.com'], true)) {
      $parts = $path->toArray();
      if ($host === 'player.vimeo.com') {
        $parts = array_slice($parts, 1); // /video/ID
      }

      $id = $parts[0] ?? null;
      if (!is_string($id) || !preg_match('!^\d+$!', $id)) {
        return null;
      }

      // Video "non in elenco": il codice sta nel percorso (vimeo.com/ID/HASH)
      // oppure in ?h=HASH (player.vimeo.com).
      $hash = $parts[1] ?? $query->h ?? null;
      $hash = (is_string($hash) && preg_match('!^[a-f0-9]{6,20}$!i', $hash)) ? $hash : null;

      return ['provider' => 'vimeo', 'id' => $id, 'hash' => $hash, 'start' => 0];
    }

    return null;
  }

  /** Indirizzo dell'iframe, già "senza cookie". */
  public static function src(array $video, bool $autoplay = false): string
  {
    if ($video['provider'] === 'youtube') {
      $params = ['rel' => 0, 'playsinline' => 1];
      if ($video['start'] > 0) {
        $params['start'] = $video['start'];
      }
      if ($autoplay) {
        $params['autoplay'] = 1;
      }

      return 'https://www.youtube-nocookie.com/embed/' . $video['id'] . '?' . http_build_query($params);
    }

    $params = ['dnt' => 1];
    if ($video['hash']) {
      $params['h'] = $video['hash'];
    }
    if ($autoplay) {
      $params['autoplay'] = 1;
    }

    return 'https://player.vimeo.com/video/' . $video['id'] . '?' . http_build_query($params);
  }
}

Kirby::plugin('consulta/video-embed', [
  'validators' => [
    // Per i blueprint: validate: videoUrl
    'videoUrl' => fn ($value) => $value === '' || $value === null || VideoEmbed::parse($value) !== null,
  ],
  // Kirby cerca la chiave in minuscolo: error.validation.videourl
  'translations' => [
    'it' => [
      'error.validation.videourl' => 'Inserisci un link a un video di YouTube o Vimeo (per esempio https://www.youtube.com/watch?v=… o https://vimeo.com/123456789).',
    ],
    'en' => [
      'error.validation.videourl' => 'Enter a link to a YouTube or Vimeo video (for example https://www.youtube.com/watch?v=… or https://vimeo.com/123456789).',
    ],
  ],
]);
