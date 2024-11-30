<?php

namespace VasArt\NaughtyWords;

class WordsList
{
    /**
     * The list of naughty words.
     *
     * @var string[]
     */
    private $words;

    /**
     * The list of naughty words as a PCRE regex.
     *
     * @var string
     */
    private $regex;

    /**
     * Get regex.
     *
     * @return string
     */
    public function getRegex(): string
    {
        return $this->regex;
    }

    /**
     * @param string[] $words
     */
    public function __construct(array $words)
    {
        // Remove empty strings and falsy values, just in case
        $words = array_filter($words);

        $this->words = $words;
        $this->updateRegex();
    }

    /**
     * Get the list of naughty words as an array.
     *
     * @return string[]
     */
    public function getAll(): array
    {
        return $this->words;
    }

    /**
     * Add an arbitrary word to the words list.
     *
     * @param string $word
     * @return void
     */
    public function add(string $word)
    {
        $this->words[] = $word;
        $this->updateRegex();
    }

    /**
     * Update stored regex using current words list.
     *
     * @return void
     */
    private function updateRegex()
    {
        $this->regex = $this->buildRegex();
    }

    /**
     * Build regex from current words list.
     *
     * @return string
     */
    private function buildRegex(): string
    {
        // Cyrillic characters borrowed from:
        // https://stackoverflow.com/a/52257128/12320578
        $wordChars = '[a-zA-ZЁёА-я]';
        $wordBoundary = "(?:(?<!$wordChars)(?=$wordChars)|(?<=$wordChars)(?!$wordChars))";

        return sprintf(
            "/$wordBoundary(?<word>(?:%s)+)$wordBoundary/",
            implode('|', $this->words)
        );
    }
}