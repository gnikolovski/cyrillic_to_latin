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
  public static $modules = [
    'locale',
    'cyrillic_to_latin',
    'cyrillic_to_latin_test',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
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
  public function testConversion() {
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
    $this->assertResponse(200);
    $this->assertText('Ово је само тест');
    $this->assertText('Ово је бодy');
    $this->assertText('Ово је из Тwига');

    $this->drupalGet('sr/node/1');
    $this->assertResponse(200);
    $this->assertText('Ovo jе samo tеst');
    $this->assertText('Ovo jе body');
    $this->assertText('Ovo jе iz Twiga');
  }

}
