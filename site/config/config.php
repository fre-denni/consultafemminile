<?php

$isStaging = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/staging/') === 0;

return [
    'debug' => $isStaging,
    'panel' => [
        'install' => $isStaging,
    ],
    'extensions' => [
        'fieldMethods' => require __DIR__ . '/methods.php',
    ],
];