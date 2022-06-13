<?php

namespace Drupal\context_stack\Plugin\views\argument_default\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a plugin for each current content entity ID from a context stack.
 */
class CurrentContentIdDeriver extends DeriverBase implements ContainerDeriverInterface {

  use StringTranslationTrait;

  /**
   * A list of providers regards current content, keyed by service ID.
   *
   * @var \Drupal\context_stack\ContextProvider\CurrentContentBase[]
   */
  protected $contextProviders = [];

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    $instance = new static();
    $instance->stringTranslation = $container->get('string_translation');
    $provider_services = [
      'context_stack.current_content',
      'context_stack.current_parent_content',
      'context_stack.current_root_content',
    ];
    foreach ($provider_services as $service_id) {
      $instance->contextProviders[$service_id] = $container->get($service_id);
    }
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    if (empty($this->derivatives)) {
      $definitions = [];
      foreach ($this->contextProviders as $service_id => $provider) {
        foreach ($provider->getAvailableContexts() as $context_id => $context) {
          $definition = $base_plugin_definition;
          $definition['title'] = $this->t('ID from @context', ['@context' => $context->getContextDefinition()->getLabel()]);
          $definitions[$service_id . ':' . $context_id] = $definition;
        }
      }
      $this->derivatives = $definitions;
    }

    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

}
