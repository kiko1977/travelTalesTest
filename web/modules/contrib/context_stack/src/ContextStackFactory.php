<?php

namespace Drupal\context_stack;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default implementation of a context stack factory.
 */
class ContextStackFactory implements ContextStackFactoryInterface {

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected EventDispatcherInterface $eventDispatcher;

  /**
   * The ContextStackFactory constructor.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   */
  public function __construct(EventDispatcherInterface $event_dispatcher) {
    $this->eventDispatcher = $event_dispatcher;
  }

  /**
   * Get the factory service.
   *
   * @return \Drupal\context_stack\ContextStackFactoryInterface
   *   The factory service.
   */
  public static function get(): ContextStackFactoryInterface {
    return \Drupal::service('context_stack.factory');
  }

  /**
   * {@inheritdoc}
   */
  public function createStack(?string $purpose = NULL): ContextStackInterface {
    return new ContextStack($this->eventDispatcher, $purpose);
  }

  /**
   * {@inheritdoc}
   */
  public function createCollection(): ContextCollectionInterface {
    return new ContextCollection();
  }

}
