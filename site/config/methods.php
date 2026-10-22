<?php

use Kirby\Content\Field;

return [
  /**
   * Tronca il testo del campo a un numero massimo di parole, aggiungendo
   * un suffisso (default "…") solo se il testo viene davvero tagliato.
   * (Kirby riserva già "words" per contare le parole — vedi kirby/config/methods.php)
   * Uso: $page->description()->truncateWords(35)
   */
  'truncateWords' => function (Field $field, int $count = 35, string $suffix = '…'): string {
    // Il campo è HTML (editor con paragrafi): uno spazio tra un blocco e
    // l'altro prima di togliere i tag, altrimenti le parole si incollano.
    $html  = preg_replace('/<\/(p|li|h[1-6]|blockquote)>|<br\s*\/?>/i', ' ', (string) $field->value());
    $text  = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

    if ($words === [] || count($words) <= $count) {
      return $text;
    }

    return implode(' ', array_slice($words, 0, $count)) . $suffix;
  },

  /**
   * Formatta un campo "date" in italiano ("16 marzo 2026"): date()
   * di PHP non è locale-aware e il progetto non configura un locale di
   * sistema, quindi i nomi dei mesi vanno tradotti a mano.
   * Uso: $page->data()->toItalianDate()
   */
  'toItalianDate' => function (Field $field): string {
    if ($field->isEmpty()) {
      return '';
    }

    $timestamp = strtotime($field->value());

    if ($timestamp === false) {
      return '';
    }

    $months = [
      'gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno',
      'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre',
    ];

    return date('j', $timestamp) . ' ' . $months[(int) date('n', $timestamp) - 1] . ' ' . date('Y', $timestamp);
  },
];
