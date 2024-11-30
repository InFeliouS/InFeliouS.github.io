<?php

namespace VasArt\NaughtyWords;

class NaughtyWords
{
    public static function getForLanguage(string $language): array
    {
        $words = file(
            dirname(__DIR__) . DIRECTORY_SEPARATOR . "{$language}",
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        // Pass words array through WordsList to apply sanitization
        $words = new WordsList($words);

        return $words->getAll();
    }
}
