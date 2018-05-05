<?php

namespace Drupal\cyrillic_to_latin;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Class CyrillicToLatinServiceProvider.
 *
 * @package Drupal\cyrillic_to_latin
 */
class CyrillicToLatinServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $config = \Drupal::configFactory()->get('cyrillic_to_latin.settings');

    if ($config->get('enabled')) {
      $definition = $container->getDefinition('string_translation');
      $definition->setClass('Drupal\cyrillic_to_latin\CyrillicToLatinManager');
    }
  }

}
