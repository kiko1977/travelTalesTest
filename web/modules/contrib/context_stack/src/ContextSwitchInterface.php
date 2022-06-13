<?php

namespace Drupal\context_stack;

/**
 * An interface for reacting upon switching the current context collection.
 */
interface ContextSwitchInterface {

  /**
   * Acting right after a collection was pushed to the context stack.
   *
   * @param \Drupal\context_stack\ContextCollectionInterface $collection
   *   The collection that got pushed to the stack.
   */
  public function pushed(ContextCollectionInterface $collection): void;

  /**
   * Acting right after a collection was popped from the context stack.
   *
   * @param \Drupal\context_stack\ContextCollectionInterface $collection
   *   The collection that got popped from the stack.
   */
  public function popped(ContextCollectionInterface $collection): void;

}
