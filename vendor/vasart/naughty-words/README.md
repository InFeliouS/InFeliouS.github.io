# php-naughty-words

A [List of Dirty, Naughty, Obscene, and Otherwise Bad Words](https://github.com/LDNOOBW/List-of-Dirty-Naughty-Obscene-and-Otherwise-Bad-Words) to use in PHP via [Composer](https://getcomposer.org/).

**Obvious warning**: These lists contain material that many will find offensive. (But that's the point!)

## Before you start

If you think that implementing an automatic filter of bad words is a good idea, you should really check these articles first:

+ [Obscenity Filters: Bad Idea, or Incredibly Intercoursing Bad Idea?](https://blog.codinghorror.com/obscenity-filters-bad-idea-or-incredibly-intercoursing-bad-idea/)
+ [Scunthorpe problem](https://en.wikipedia.org/wiki/Scunthorpe_problem)

To sum it up, this library by no way should be used as a 100% way to get rid of obscene language in your application. People will always find out ways to bypass your filters. If you need to handle bad words, you need manual control. Tools like this one are meant to assist human moderation, not to replace it.

Use cases, where this library might be of use:

1. Requesting an approval from a moderator after user input validation when potentially bad words are found.
2. Refuse user input only when an exact match of a bad word is found.<br>
   (This is easily bypassed when a bad word is given one more arbitrary letter, just so the exact match won't succeed.)

## Installation 

```bash
composer require vasart/naughty-words
```

## Usage

Receiving a plain list of naughty words:

```php
use VasArt\NaughtyWords\NaughtyWords;

$naughtyWordsEn = NaughtyWords::getForLanguage('en');
```

The string `'en'` inside `getForLanguage()` call here is the name of the file with bad words for the language of choice. See the list of available languages in [Languages](#Languages) section.

Using built-in validator:

```php
use VasArt\NaughtyWords\Validator;

$text = 'some user input with potentially bad words';

$naughtyWordsValidator = new Validator( [ 'en', 'ru' ] );
$naughtyWords = $naughtyWordsValidator->findNaughtyWords( $text );

var_export($naughtyWords); // [ 'en' => 'word', 'ru' => false ]
```

For examining how does built-in validator work you can check:

+ test cases inside [ValidatorTest](test/ValidatorTest.php) class;
+ regular expression that is built inside [WordsList](src/WordsList.php) class.

## Languages

| Name                               | Code              |
| ---------------------------------- | ----------------- |
| [Arabic](ar)                       | ar                |
| [Chinese](zh)                      | zh                |
| [Czech](cs)                        | cs                |
| [Danish](da)                       | da                |
| [Dutch](nl)                        | nl                |
| [English](en)                      | en                |
| [Esperanto](eo)                    | eo                |
| [Filipino](fil)                    | fil               |
| [Finnish](fi)                      | fi                |
| [French](fr)                       | fr                |
| [French (CA)](fr-CA-u-sd-caqc)     | fr-CA-u-sd-caqc   |
| [German](de)                       | de                |
| [Hindi](hi)                        | hi                |
| [Hungarian](hu)                    | hu                |
| [Italian](it)                      | it                |
| [Japanese](ja)                     | ja                |
| [Kabyle](kab)                      | kab               |
| [Klingon](tlh)                     | tlh               |
| [Korean](ko)                       | ko                |
| [Norwegian](no)                    | no                |
| [Persian](fa)                      | fa                |
| [Polish](pl)                       | pl                |
| [Portuguese](pt)                   | pt                |
| [Russian](ru)                      | ru                |
| [Spanish](es)                      | es                |
| [Swedish](sv)                      | sv                |
| [Thai](th)                         | th                |
| [Turkish](tr)                      | tr                |

## Other installation options

If you need to use bad words inside an `npm` project, you can install the word list using the [naughty-words](https://github.com/LDNOOBW/naughty-words-js) package.

## License

The code, configuration and project description files are licensed under [GNU GPL 3.0](https://www.gnu.org/licenses/gpl-3.0.en.html), see [LICENSE](LICENSE).

The list of words is licensed under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/), see [LICENSE.words](LICENSE.words). © 2012–2020 Shutterstock, Inc.

## More naughty words

+ [expletives](https://github.com/alvations/expletives)
+ [google-profanity-words](https://github.com/coffee-and-fun/google-profanity-words)
+ [encycloDB / Dirty Words](https://github.com/turalus/encycloDB/tree/master/Dirty%20Words)
