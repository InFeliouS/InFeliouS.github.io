<?php

namespace VasArt\NaughtyWords;

class Validator
{
    /**
     * @var string[]
     */
    private $languages;

    /**
     * @var WordsList[]
     */
    private $wordsLists;

    public function __construct(array $languages)
    {
        $this->languages = $languages;

        foreach ($this->languages as $language) {
            $words = NaughtyWords::getForLanguage($language);

            if (!$words) {
                $words = [];
            }

            $wordsList = new WordsList($words);

            $this->wordsLists[$language] = $wordsList;
        }
    }

    /**
     * Finds naughty words in the given text and returns them in the array of this shape:
     * ```php
     *  [
     *      'ru' => false,
     *      'en' => 'word',
     *  ]
     * ```
     * where `false` means naughty words were not found and string 'word'
     *  signifies a naughty word that was found for respective language.
     *
     * @param string $text
     * @return array
     */
    public function findNaughtyWords(string $text): array
    {
        $text = mb_strtolower($text, 'UTF-8');

        $matchResults = [];

        foreach ($this->wordsLists as $language => $wordsList) {
            $regex = $wordsList->getRegex();

            $matchResult = preg_match($regex, $text, $matches);

            // If the match is found, assign the word or `true`.
            // If not, assign `false`.
            if ($matchResult === 1) {
                $matchResultToReturn = $matches['word'] ?? true;
            } else {
                $matchResultToReturn = false;
            }

            $matchResults[$language] = $matchResultToReturn;
        }

        return $matchResults;
    }

    /**
     * Determines whether the given text has naughty words or not.
     *
     * @param string $text
     * @return bool
     */
    public function hasNaughtyWords(string $text): bool
    {
        $matchResults = $this->findNaughtyWords($text);

        // Filter falsy values.
        $matchResultsTruthy = array_filter(array_values($matchResults));

        // At this point, if there is at least one value in the `$matchResults` array, there are naughty words.
        if (count($matchResultsTruthy) > 0) {
            return true;
        }

        // Otherwise, return `false`
        return false;
    }

    public function addWord(string $word, string $language)
    {
        if (!isset($this->wordsLists[$language])) {
            $this->wordsLists[$language] = new WordsList([]);
        }

        $this->wordsLists[$language]->add($word);
    }
}
