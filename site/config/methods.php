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
    $text  = trim(strip_tags((string) $field->value()));
    $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

    if ($words === [] || count($words) <= $count) {
      return $text;
    }

    return implode(' ', array_slice($words, 0, $count)) . $suffix;
  },
];
