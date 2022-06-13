<?php

namespace Drupal\context_stack;

/**
 * Trait for classes that use a context stack factory.
 */
trait ContextStackFactoryTrait {

  /**
   * The initialized context stack factory, or NULL if not yet loaded.
   *
   * @var \Drupal\context_stack\ContextStackFactoryInterface|null
   */
  protected ?ContextStackFactoryInterface $contextStackFactory;

  /**
   * Get the context stack factory.
   *
   * @return \Drupal\context_stack\ContextStackFactoryInterface
   *   The context stack factory.
   */
  public function getContextStackFactory(): ContextStackFactoryInterface {
    if (!isset($this->contextStackFactory)) {
      $this->contextStackFactory = \Drupal::service('context_stack.factory');
    }
    return $this->contextStackFactory;
  }

  /**
   * Sets the context stack factory.
   *
   * @param \Drupal\context_stack\ContextStackFactoryInterface $context_stack_factory
   *   The context stack factory to set.
   */
  public function setContextStackFactory(ContextStackFactoryInterface $context_stack_factory): void {
    $this->contextStackFactory = $context_stack_factory;
  }

}
