<?php

namespace Drupal\cyrillic_to_latin;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\StringTranslation\TranslationManager;

/**
 * Defines a chained translation implementation combining multiple translators.
 */
class CyrillicToLatinManager extends TranslationManager {

  /**
   * Translates a string to the current language or to a given language.
   *
   * @param string $string
   *   A string containing the English text to translate.
   * @param array $options
   *   An associative array of additional options, with the following elements:
   *   - 'langcode': The language code to translate to a language other than
   *      what is used to display the page.
   *   - 'context': The context the source string belongs to.
   *
   * @return string
   *   The translated string.
   */
  protected function doTranslate($string, array $options = []) {
    // If a NULL langcode has been provided, unset it.
    if (!isset($options['langcode']) && array_key_exists('langcode', $options)) {
      unset($options['langcode']);
    }

    // Merge in options defaults.
    $options = $options + [
        'langcode' => $this->defaultLangcode,
        'context' => '',
      ];
    $translation = $this->getStringTranslation($options['langcode'], $string, $options['context']);
    return $translation === FALSE ? $string : $this->convertCyrillicToLatin($translation);
  }

  /**
   * Convert cyrillic to latin.
   * @param string $string
   *
   * @return $string
   */
  protected function convertCyrillicToLatin($string) {
    $cyrillic  = array(
      'њ', 'љ', 'а', 'б', 'в', 'г', 'д', 'ђ', 'e', 'ж', 'з', 'и', 'ј', 'к', 'л',
      'м', 'н', 'о', 'п', 'р', 'с', 'т', 'ћ', 'у', 'ф', 'х', 'ц', 'ч', 'џ', 'ш',
      'Њ', 'Љ', 'А', 'Б', 'В', 'Г', 'Д', 'Ђ', 'Е', 'Ж', 'З', 'И', 'Ј', 'К', 'Л',
      'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'Ћ', 'У', 'Ф', 'Х','Ц', 'Ч', 'Џ', 'Ш');

    $latin = array(
      'nj', 'lj', 'a', 'b', 'v', 'g', 'd', 'đ', 'e', 'ž', 'z', 'i', 'j', 'k',
      'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'ć', 'u', 'f', 'h', 'c', 'č',
      'dž', 'š', 'Nj', 'Lj', 'A', 'B', 'V', 'G', 'D', 'Đ', 'E', 'Ž', 'Z', 'I',
      'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'Ć', 'U', 'F', 'H', 'C',
      'Č', 'Dž', 'Š');
    
    return str_replace($cyrillic, $latin, $string);
  }

}
