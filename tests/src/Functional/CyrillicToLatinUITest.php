<?php

namespace Drupal\Tests\cyrillic_to_latin\Functional;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the user interface.
 *
 * @group cyrillic_to_latin
 */
class CyrillicToLatinUITest extends BrowserTestBase {

  use StringTranslationTrait;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'config',
    'language',
    'cyrillic_to_latin',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalLogin($this->drupalCreateUser(['administer site configuration']));

    $language = ConfigurableLanguage::createFromLangcode('sr');
    $language->save();
  }

  /**
   * Tests form structure.
   */
  public function testFormStructure() {
    $this->drupalGet('admin/config/regional/cyrillic-to-latin');
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->titleEquals('Cyrillic to Latin settings | Drupal');
    $this->assertSession()->selectExists('enabled');
    $this->assertSession()->elementExists('css', '#edit-languages-en');
    $this->assertSession()->checkboxNotChecked('languages[en]');
    $this->assertSession()->elementExists('css', '#edit-languages-sr');
    $this->assertSession()->checkboxChecked('languages[sr]');
    $this->assertSession()->buttonExists($this->t('Save configuration'));
  }

  /**
   * Tests form submit.
   */
  public function testFormSubmit() {
    $form_values = [
      'enabled' => '0',
      'languages[en]' => TRUE,
      'languages[sr]' => FALSE,
    ];

    $this->drupalPostForm('admin/config/regional/cyrillic-to-latin', $form_values, 'Save configuration');
    $this->assertSession()->pageTextContains($this->t('The configuration options have been saved. You must clear the cache for the change to take effect.'));
    $this->assertSession()->fieldValueEquals('enabled', '0');
    $this->assertSession()->checkboxChecked('languages[en]');
    $this->assertSession()->checkboxNotChecked('languages[sr]');
  }

  /**
   * Tests form access.
   */
  public function testFormAccess() {
    $this->drupalLogout();
    $this->drupalGet('admin/config/regional/cyrillic-to-latin');
    $this->assertSession()->statusCodeEquals(403);
  }

}
