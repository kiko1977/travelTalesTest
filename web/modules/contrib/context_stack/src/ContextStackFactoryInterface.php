<?php

namespace Drupal\context_stack;

/**
 * Interface for context stack factories.
 */
interface ContextStackFactoryInterface {

  /**
   * Creates a new context stack.
   *
   * @param string|null $purpose
   *   (Optional) The purpose of this stack. A purpose can be used to identify
   *   a concrete meaning of all contexts that are being held by this stack.
   *
   * @return \Drupal\context_stack\ContextStackInterface
   *   The new context stack object.
   */
  public function createStack(?string $purpose = NULL): ContextStackInterface;

  /**
   * Creates a new context collection.
   *
   * @return \Drupal\context_stack\ContextCollectionInterface
   *   The new context collection object.
   */
  public function createCollection(): ContextCollectionInterface;

}
