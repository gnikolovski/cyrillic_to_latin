<?php

namespace Drupal\cyrillic_to_latin\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\cyrillic_to_latin\CyrillicToLatinManager;
use Drupal\locale\LocaleEvent;
use Drupal\locale\StringStorageInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LocaleSubscriber.
 */
class LocaleSubscriber implements EventSubscriberInterface {

  /**
   * The config.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * The locale storage.
   *
   * @var \Drupal\locale\StringStorageInterface
   */
  protected $localeStorage;

  /**
   * Constructs a new FormAjaxSubscriber.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\locale\StringStorageInterface $locale_storage
   *   The locale storage.
   */
  public function __construct(ConfigFactoryInterface $config_factory, StringStorageInterface $locale_storage) {
    $this->config = $config_factory->get('cyrillic_to_latin.settings');
    $this->localeStorage = $locale_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['locale.save_translation'] = ['localeSaveTranslation'];
    return $events;
  }

  /**
   * This method is called when the locale.save_translation is dispatched.
   *
   * @param \Drupal\locale\LocaleEvent $event
   *   The dispatched event.
   */
  public function localeSaveTranslation(LocaleEvent $event) {
    if (!$this->config->get('enabled') || !$this->config->get('transliterate_on_po_import')) {
      return;
    }

    $languages = !empty($this->config->get('languages')) ? array_filter(array_values($this->config->get('languages'))) : [];

    $lids = $event->getLids();
    foreach ($lids as $lid) {
      foreach ($languages as $language) {
        $strings = $this->localeStorage->getTranslations([
          'lid' => $lid,
          'language' => $language,
          'translated' => TRUE,
        ]);
        foreach ($strings as $string) {
          $original_string = $string->getString();
          $new_string = CyrillicToLatinManager::convertCyrillicToLatin($original_string);
          $string->setString($new_string)->save();
        }
      }
    }
  }

}
