<?php

namespace Drupal\context_stack;

use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Trait for classes that use one or multiple context stacks.
 */
trait ContextStackTrait {

  /**
   * A list of initialized context stacks used by this class.
   *
   * @var \Drupal\context_stack\ContextStackInterface[]
   */
  protected array $contextStacks = [];

  /**
   * Used for storing stack-related information for serialization.
   *
   * @var array
   */
  protected array $contextStackSerialization = [];

  /**
   * Get a context stack having a specified purpose.
   *
   * @param string $purpose
   *   The purpose of the stack, e.g. "view" or "account".
   *
   * @return \Drupal\context_stack\ContextStackInterface
   *   The requested context stack.
   */
  public function getContextStack(string $purpose): ?ContextStackInterface {
    if (!isset($this->contextStacks[$purpose])) {
      $service_id = 'context_stack.' . $purpose;
      $context_stack = \Drupal::hasService($service_id) ? \Drupal::service($service_id) : $this->getContextStackRepository()->get($purpose);
      if ($context_stack) {
        $this->contextStacks[$purpose] = $context_stack;
      }
    }
    return $this->contextStacks[$purpose] ?? NULL;
  }

  /**
   * Get the repository of all known context stacks.
   *
   * @return \Drupal\context_stack\ContextStackRepositoryInterface
   *   The context stack repository.
   */
  public function getContextStackRepository(): ContextStackRepositoryInterface {
    return \Drupal::service('context_stack.repository');
  }

  /**
   * Adds a context stack.
   *
   * @param \Drupal\context_stack\ContextStackInterface $context_stack
   *   The context stack to add.
   *
   * @return $this
   *   This object.
   */
  public function addContextStack(ContextStackInterface $context_stack) {
    if ($purpose = $context_stack->getPurpose()) {
      $this->contextStacks[$purpose] = $context_stack;
    }
    else {
      $this->contextStacks[] = $context_stack;
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   *
   * Additionally returns the cache context ID 'context_stack' and all cache
   * contexts from used context stacks.
   */
  public function getCacheContexts() {
    $contexts = [];
    $parent_class = func_num_args() ? func_get_arg(0) : get_class($this);
    while ($parent_class = get_parent_class($parent_class)) {
      if (method_exists($parent_class, __FUNCTION__)) {
        $contexts = $parent_class::getCacheContexts($parent_class);
        break;
      }
    }
    if (empty($this->contextStacks)) {
      if ($this->contextStackCacheContextIsAvailable()) {
        $contexts[] = 'context_stack';
      }
    }
    else {
      // A context stack automatically contains a 'context_stack' cache context.
      foreach ($this->contextStacks as $stack) {
        $contexts = array_merge($contexts, $stack->getCacheContexts());
      }
    }
    return array_unique($contexts);
  }

  /**
   * {@inheritdoc}
   *
   * Performs a check whether this object uses a stack that does not have
   * a specified purpose. In such case, we need to disable caching on this.
   */
  public function getCacheMaxAge() {
    if (!$this->contextStackCacheContextIsAvailable()) {
      // We cannot cache items using a context stack, which in turn is not
      // available anymore.
      return 0;
    }
    foreach ($this->contextStacks as $stack) {
      if (!$stack->hasPurpose()) {
        // Context stacks without a specified purpose cannot be identified
        // as a service, and by this we cannot cache object using such stacks.
        return 0;
      }
    }
    $parent_class = func_num_args() ? func_get_arg(0) : get_class($this);
    while ($parent_class = get_parent_class($parent_class)) {
      if (method_exists($parent_class, __FUNCTION__)) {
        return $parent_class::getCacheMaxAge($parent_class);
      }
    }
    return CacheBackendInterface::CACHE_PERMANENT;
  }

  /**
   * {@inheritdoc}
   *
   * Adds cache tags from all used context stacks.
   */
  public function getCacheTags() {
    $tags = [];
    $parent_class = func_num_args() ? func_get_arg(0) : get_class($this);
    while ($parent_class = get_parent_class($parent_class)) {
      if (method_exists($parent_class, __FUNCTION__)) {
        $tags = $parent_class::getCacheTags($parent_class);
        break;
      }
    }
    foreach ($this->contextStacks as $stack) {
      $tags = array_merge($tags, $stack->getCacheTags());
    }
    return array_unique($tags);
  }

  /**
   * {@inheritdoc}
   *
   * Unsets assigned context stacks having a purpose,
   * but keeping their purpose in mind for unserialization.
   */
  public function __sleep() {
    $vars = [];
    $parent_class = func_num_args() ? func_get_arg(0) : get_class($this);
    while ($parent_class = get_parent_class($parent_class)) {
      if (method_exists($parent_class, __FUNCTION__)) {
        $vars = $parent_class::__sleep($parent_class);
        break;
      }
    }
    // We won't serialize stacks having a purpose. Instead those will be
    // restored by their service counterpart. Stacks having no purpose will
    // be completely serialized and passed through when unserialized.
    foreach ($this->contextStacks as $purpose => $stack) {
      if ($stack->hasPurpose()) {
        $this->contextStackSerialization['purpose'][] = $purpose;
      }
      else {
        // Keep the whole stack if it has no purpose.
        $this->contextStackSerialization['no_purpose'][] = $stack;
      }
    }
    return array_filter($vars, function ($value) {
      return !in_array($value, ['contextStacks']);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function __wakeup() {
    $parent_class = func_num_args() ? func_get_arg(0) : get_class($this);
    while ($parent_class = get_parent_class($parent_class)) {
      if (method_exists($parent_class, __FUNCTION__)) {
        $parent_class::__wakeup($parent_class);
        break;
      }
    }
    $this->contextStacks = [];
    if (!empty($this->contextStackSerialization['purpose'])) {
      foreach ($this->contextStackSerialization['purpose'] as $purpose) {
        $this->getContextStack($purpose);
      }
    }
    if (!empty($this->contextStackSerialization['no_purpose'])) {
      foreach ($this->contextStackSerialization['no_purpose'] as $stack) {
        $this->addContextStack($stack);
      }
    }
    $this->contextStackSerialization = [];
  }

  /**
   * Check whether the service for "context_stack" cache contexts is available.
   *
   * @return bool
   *   Returns TRUE if available, FALSE otherwise.
   */
  protected function contextStackCacheContextIsAvailable(): bool {
    return \Drupal::hasContainer() && in_array('context_stack', \Drupal::getContainer()->getParameter('cache_contexts'));
  }

}
