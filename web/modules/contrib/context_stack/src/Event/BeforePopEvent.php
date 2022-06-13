<?php

namespace Drupal\context_stack\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\context_stack\ContextCollectionInterface;

/**
 * Dispatches before a collection is being popped off the stack.
 */
class BeforePopEvent extends Event {

  /**
   * The according context collection.
   *
   * @var \Drupal\context_stack\ContextCollectionInterface
   */
  protected ContextCollectionInterface $collection;

  /**
   * The BeforePopEvent constructor.
   *
   * @param \Drupal\context_stack\ContextCollectionInterface $collection
   *   The according context collection.
   */
  public function __construct(ContextCollectionInterface $collection) {
    $this->collection = $collection;
  }

}
