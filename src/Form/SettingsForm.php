<?php

namespace Drupal\cyrillic_to_latin\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form that configures Cyrillic to Latin settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(ConfigFactoryInterface $config_factory, LanguageManagerInterface $language_manager) {
    parent::__construct($config_factory);
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cyrillic_to_latin_admin_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cyrillic_to_latin.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $cyrillic_to_latin_config = $this->config('cyrillic_to_latin.settings');

    $form['enabled'] = [
      '#type' => 'select',
      '#title' => $this->t('Enabled'),
      '#default_value' => $cyrillic_to_latin_config->get('enabled'),
      '#description' => $this->t('Enable or disable the module. You must clear the cache for the change to take effect.'),
      '#options' => [
        '0' => $this->t('No'),
        '1' => $this->t('Yes'),
      ],
    ];

    $form['languages'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Languages'),
      '#default_value' => !empty($cyrillic_to_latin_config->get('languages')) ? $cyrillic_to_latin_config->get('languages') : [],
      '#description' => $this->t('Apply transliteration only for selected languages.'),
      '#options' => $this->getLanguages(),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('cyrillic_to_latin.settings')
      ->set('enabled', $values['enabled'])
      ->set('languages', $values['languages'])
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Gets available languages.
   *
   * @return array
   *   An array of available languages.
   */
  protected function getLanguages() {
    $languages = [];

    foreach ($this->languageManager->getLanguages() as $language) {
      $languages[$language->getId()] = $language->getName();
    }

    return $languages;
  }

}
