<?php

namespace Drupal\Tests\cyrillic_to_latin\Functional;

use Drupal\filter\Entity\FilterFormat;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\node\Traits\ContentTypeCreationTrait;

/**
 * Tests the conversion.
 *
 * @group cyrillic_to_latin
 */
class CyrillicToLatinConversionTest extends BrowserTestBase {

  use ContentTypeCreationTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'locale',
    'cyrillic_to_latin',
    'cyrillic_to_latin_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $language = ConfigurableLanguage::createFromLangcode('sr');
    $language->save();

    $this->drupalCreateContentType([
      'type' => 'page',
      'name' => 'Page',
    ]);
  }

  /**
   * Tests form structure.
   */
  public function testFormStructure() {
    $full_html_format = FilterFormat::create([
      'format' => 'full_html',
      'name' => 'Full HTML',
      'weight' => 1,
      'filters' => [],
    ]);
    $full_html_format->save();

    $node = $this->drupalCreateNode([
      'type' => 'page',
      'title' => 'Ово је само тест',
      'body' => [
        ['value' => 'Ово је бодy', 'format' => 'full_html'],
      ],
    ]);

    $node_sr = $node->addTranslation('sr');
    $node_sr->title = 'Ово је само тест';
    $node_sr->body = [
      ['value' => 'Ово је бодy', 'format' => 'full_html'],
    ];
    $node_sr->save();

    $this->drupalGet('node/1');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Ово је само тест');
    $this->assertSession()->pageTextContains('Ово је бодy');
    $this->assertSession()->pageTextContains('Ово је из Тwига');

    $this->drupalGet('sr/node/1');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->pageTextContains('Ovo je samo test');
    $this->assertSession()->pageTextContains('Ovo je body');
    $this->assertSession()->pageTextContains('Ovo je iz Twiga');
  }

}
