<?php

namespace Drupal\context_stack\Plugin\views\argument_default;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\Entity\EntityInterface;
use Drupal\views\Plugin\views\argument_default\ArgumentDefaultPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Default argument plugin to extract the current content entity ID.
 *
 * @ViewsArgumentDefault(
 *   id = "current_content_id",
 *   title = @Translation("Current content entity ID"),
 *   deriver = "Drupal\context_stack\Plugin\views\argument_default\Derivative\CurrentContentIdDeriver",
 * )
 */
class CurrentContentId extends ArgumentDefaultPluginBase implements CacheableDependencyInterface {

  use RefinableCacheableDependencyTrait;

  /**
   * A list of contexts to use for retrieving the value for this plugin.
   *
   * @var \Drupal\Core\Plugin\Context\ContextInterface[]
   */
  protected array $contexts = [];

  /**
   * Constructs a CurrentContentId object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Plugin\Context\ContextInterface[] $contexts
   *   A list of contexts to use for retrieving the value for this plugin.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, array $contexts) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    foreach ($contexts as $context) {
      $this->addCacheableDependency($context);
      $this->contexts[] = $context;
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    list(, $service_id, $context_id) = explode(':', $plugin_id, 3);
    /** @var \Drupal\context_stack\ContextProvider\CurrentContentBase $context_provider */
    $context_provider = $container->get($service_id);
    return new static($configuration, $plugin_id, $plugin_definition, $context_provider->getRuntimeContexts([$context_id]));
  }

  /**
   * {@inheritdoc}
   */
  public function getArgument() {
    foreach ($this->contexts as $context) {
      if ($context->hasContextValue()) {
        $value = $context->getContextValue();
        if ($value instanceof EntityInterface) {
          return $value->id();
        }
      }
    }
    return NULL;
  }

}
