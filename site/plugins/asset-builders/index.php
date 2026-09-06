<?php

function bundledAsset(string $group, array $patterns, string $ext = 'css'): string
{
    $kirby = kirby();

    $files = [];
    foreach ($patterns as $pattern) {
        $files = array_merge($files, glob($kirby->root('site') . '/' . $pattern));
    }
    sort($files); // ordine stabile: atoms -> bits -> blocks -> snippets

    // firma basata su path + data modifica: cambia solo se cambia qualcosa
    $signature = '';
    foreach ($files as $file) {
        $signature .= $file . filemtime($file);
    }
    $hash = substr(md5($signature), 0, 10);

    $mediaRoot = $kirby->root('media') . '/assets';
    $filename  = "{$group}.{$hash}.{$ext}";
    $mediaPath = "{$mediaRoot}/{$filename}";

    if (!is_file($mediaPath)) {
        Dir::make($mediaRoot);
        $content = '';
        foreach ($files as $file) {
            $content .= file_get_contents($file) . "\n";
        }
        F::write($mediaPath, $content);

        // rimuove i bundle vecchi dello stesso gruppo
        foreach (glob("{$mediaRoot}/{$group}.*.{$ext}") as $old) {
            if ($old !== $mediaPath) F::remove($old);
        }
    }

    return $kirby->url('media') . "/assets/{$filename}";
}