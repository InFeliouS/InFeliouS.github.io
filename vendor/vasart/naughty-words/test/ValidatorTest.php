<?php

namespace VasArt\NaughtyWords;

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @var Validator
     */
    private $validator;

    public function __construct(string $name = null, array $data = [], $dataName = '')
    {
        $this->validator = new Validator(['ru', 'en']);

        $this->validator->addWord('ruword', 'ru');
        $this->validator->addWord('anotherword', 'ru');
        $this->validator->addWord('enword', 'en');

        parent::__construct($name, $data, $dataName);
    }

    public function testHasNaughtyWords()
    {
        $cases = [
            [
                'text' => 'ruword',
                'description' => 'Should detect naughty word when it exactly matches the text.',
                'assertEquals' => true
            ],
            [
                'text' => 'abc ruword',
                'description' => 'Should detect naughty word when there are spaces before it.',
                'assertEquals' => true
            ],
            [
                'text' => 'ruword abc',
                'description' => 'Should detect naughty word when there are spaces after it.',
                'assertEquals' => true
            ],
            [
                'text' => '123ruword',
                'description' => 'Should detect naughty word when there are numbers before it.',
                'assertEquals' => true
            ],
            [
                'text' => 'ruword123',
                'description' => 'Should detect naughty word when there are numbers after it.',
                'assertEquals' => true
            ],
            [
                'text' => '123ruword123',
                'description' => 'Should detect naughty word when there are numbers to the both sides.',
                'assertEquals' => true
            ],
            [
                'text' => 'abcruword',
                'description' => 'Should not detect naughty word when there are letter characters before it.',
                'assertEquals' => false
            ],
            [
                'text' => 'ruwordabc',
                'description' => 'Should not detect naughty word when there are letter characters after it.',
                'assertEquals' => false
            ],
            [
                'text' => 'abcruwordabc',
                'description' => 'Should not detect naughty word when there are letter characters to the both sides.',
                'assertEquals' => false
            ],
            [
                'text' => 'abc-abc-ruword-abc',
                'description' => 'Should detect naughty word when it is separated with a dash.',
                'assertEquals' => true
            ],
            [
                'text' => 'abc-abc----ruword-abc',
                'description' => 'Should detect naughty word when it is separated with a dash (one or more).',
                'assertEquals' => true
            ],
            [
                'text' => 'abc_abc_ruword_abc',
                'description' => 'Should detect naughty word when it is separated with an underscore.',
                'assertEquals' => true
            ],
            [
                'text' => 'abc_abc___ruword_abc',
                'description' => 'Should detect naughty word when it is separated with an underscore (one or more).',
                'assertEquals' => true
            ],
            [
                'text' => 'ruwordruwordruword',
                'description' => 'Should detect naughty word when it is repeated several times.',
                'assertEquals' => true
            ],
            [
                'text' => '123ruwordruword-ruwordruword_ruwordruword123',
                'description' => 'Should detect naughty word when it is in combination with repetition and separation with numbers, dashes or underscores.',
                'assertEquals' => true
            ],
            [
                'text' => 'ruwordanotherword',
                'description' => 'Should detect naughty word when it is joined with other naughty word.',
                'assertEquals' => true
            ],
            [
                'text' => 'ruwordanotherwordruwordruwordanotherword',
                'description' => 'Should detect naughty word when it is joined with another word and with itself multiple times.',
                'assertEquals' => true
            ],
            [
                'text' => 't ruwordanotherwordt',
                'description' => 'Should not detect naughty word when it is joined with other naughty word but they are the part of another word.',
                'assertEquals' => false
            ],
        ];

        foreach ($cases as $case) {
            $text = $case['text'];
            $validationResult = $this->validator->hasNaughtyWords($text);
            $this->assertEquals($case['assertEquals'], $validationResult, $case['description']);
        }
    }
}
