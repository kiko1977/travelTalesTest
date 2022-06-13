<?php

namespace Drupal\context_stack_account\EventSubscriber;

use Drupal\context_stack\ContextStackFactoryInterface;
use Drupal\context_stack\ContextStackFactoryTrait;
use Drupal\context_stack\ContextStackInterface;
use Drupal\context_stack\ContextStackTrait;
use Drupal\context_stack\Plugin\Context\GenericEntityContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event subscriber that wraps controller processing with a context stack.
 */
class ControllerWrapperSubscriber implements EventSubscriberInterface {

  use ContextStackTrait;
  use ContextStackFactoryTrait;

  /**
   * A static collection of context collections, keyed by purpose.
   *
   * @var \Drupal\context_stack\ContextCollectionInterface[]
   */
  protected static array $contextCollections = [];

  /**
   * The ControllerWrapperSubscriber constructor.
   *
   * @param \Drupal\context_stack\ContextStackInterface $context_stack_account
   *   The context stack with current "account" purpose.
   * @param \Drupal\context_stack\ContextStackFactoryInterface $context_stack_factory
   *   The factory for context stack objects.
   */
  public function __construct(ContextStackInterface $context_stack_account, ContextStackFactoryInterface $context_stack_factory) {
    $this->addContextStack($context_stack_account);
    $this->contextStackFactory = $context_stack_factory;
  }

  /**
   * Pushes early collections to the "view" and "account" context stacks.
   *
   * @param \Symfony\Component\HttpKernel\Event\ControllerEvent $event
   *   The controller event.
   */
  public function onKernelController(ControllerEvent $event) {
    $new_collections = [];

    if (!isset(static::$contextCollections['account']) && ($user = \Drupal::entityTypeManager()->getStorage('user')->load(\Drupal::currentUser()->id()))) {
      $collection = $this->contextStackFactory->createCollection();
      $collection->addContext(GenericEntityContext::fromEntity($user, t('Current user account')), 'user');
      $new_collections['account'] = $collection;
    }

    foreach ($new_collections as $purpose => $collection) {
      $this->getContextStack($purpose)->push($collection);
      static::$contextCollections[$purpose] = $collection;
    }
  }

  /**
   * Pops previously pushed collections from the context stacks.
   *
   * @param \Symfony\Component\HttpKernel\Event\FinishRequestEvent $event
   *   The according event.
   */
  public function onKernelRequestFinish(FinishRequestEvent $event) {
    foreach (static::$contextCollections as $purpose => $collection) {
      $stack = $this->getContextStack($purpose);
      if (!($popped = $stack->pop())) {
        \Drupal::logger('context_stack')->error(sprintf("Got an unexpected state of the '%s' context stack. The stack is empty, though a collection was added by ContextStackControllerWrapperSubscriber. Every component that adds a context collection needs to make sure on its own, that it only removes items that belong to itself.", $stack->getPurpose()));
        continue;
      }
      if (!($popped === $collection)) {
        \Drupal::logger('context_stack')->error(sprintf("Got an unexpected state of the '%s' context stack. The removed context collection is a different one that was initially added. Every component that adds a context collection needs to make sure on its own, that the collection is being removed.", $stack->getPurpose()));
      }
    }
    static::$contextCollections = [];
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::CONTROLLER][] = ['onKernelController', 11];
    $events[KernelEvents::FINISH_REQUEST][] = ['onKernelRequestFinish'];
    return $events;
  }

}
