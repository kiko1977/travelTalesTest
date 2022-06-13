<?php

namespace Drupal\context_stack;

use Drupal\context_stack\Event\AfterPopEvent;
use Drupal\context_stack\Event\AfterPushEvent;
use Drupal\context_stack\Event\BeforePopEvent;
use Drupal\context_stack\Event\BeforePushEvent;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Plugin\Context\ContextInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Default context stack service implementation.
 */
class ContextStack extends ContextCollection implements ContextStackInterface {

  use ContextStackFactoryTrait;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected EventDispatcherInterface $eventDispatcher;

  /**
   * The purpose of this stack, or NULL if not defined.
   *
   * @var string|null
   */
  protected ?string $purpose;

  /**
   * A collection of stacked context collections.
   *
   * @var \Drupal\context_stack\ContextCollectionInterface[]
   */
  protected array $collections = [];

  /**
   * Currently stacked context collections, keyed by their checksum.
   *
   * @var \Drupal\context_stack\ContextCollectionInterface[]
   */
  protected array $collectionsByChecksum = [];

  /**
   * Contains checksums from any previously added collections.
   *
   * @var string[]
   */
  protected array $checksums = [];

  /**
   * Cacheable metadata from collections that othwerwise would get lost.
   *
   * Keyed by collection checksum.
   *
   * @var \Drupal\Core\Cache\CacheableMetadata[]
   */
  protected array $cacheableMetadataMemory = [];

  /**
   * The current nesting level.
   *
   * @var int
   */
  protected int $nestingLevel = 0;

  /**
   * The ContextStack constructor.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   * @param string|null $purpose
   *   (Optional) The purpose of this stack. A purpose can be used to identify
   *   a concrete meaning of all contexts that are being held by this stack.
   */
  public function __construct(EventDispatcherInterface $event_dispatcher, ?string $purpose = NULL) {
    $this->eventDispatcher = $event_dispatcher;
    $this->purpose = $purpose;
  }

  /**
   * {@inheritdoc}
   */
  public function push(ContextCollectionInterface $collection): ContextStackInterface {
    if (in_array($collection, $this->collections, TRUE)) {
      throw new \LogicException("Cannot push a context collection more than once.");
    }
    if ($this->nestingLevel >= 256) {
      throw new \RuntimeException("Nesting level too high! Aborted addition of further collections to the context stack.");
    }
    $this->eventDispatcher->dispatch(new BeforePushEvent($collection), ContextStackEvents::PUSH_BEFORE);
    $parent = $this->current();
    array_push($this->collections, $collection);
    $this->nestingLevel++;
    $collection->setParent($parent, $this);
    $collection->pushed($collection);
    $checksum = $collection->generateChecksum();
    $checksum_index = array_search($checksum, $this->checksums, TRUE);
    if ($checksum_index !== FALSE) {
      unset($this->checksums[$checksum_index]);
    }
    array_push($this->checksums, $checksum);
    if (count($this->checksums) > 256) {
      array_shift($this->checksums);
      array_shift($this->cacheableMetadataMemory);
    }
    unset($this->collectionsByChecksum[$checksum]);
    $this->collectionsByChecksum += [$checksum => $collection];
    $this->eventDispatcher->dispatch(new AfterPushEvent($collection), ContextStackEvents::PUSH_AFTER);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function pop(): ?ContextCollectionInterface {
    if (!($collection = $this->current())) {
      return NULL;
    }

    $this->eventDispatcher->dispatch(new BeforePopEvent($collection), ContextStackEvents::POP_BEFORE);
    if ($checksum = array_search($collection, $this->collectionsByChecksum, TRUE)) {
      unset($this->cacheableMetadataMemory[$checksum]);
      $this->cacheableMetadataMemory += [$checksum => CacheableMetadata::createFromObject($collection)];
      unset($this->collectionsByChecksum[$checksum]);
    }
    $collection = array_pop($this->collections);
    $this->nestingLevel--;
    $collection->popped($collection);
    $this->eventDispatcher->dispatch(new AfterPopEvent($collection), ContextStackEvents::POP_AFTER);
    return $collection;
  }

  /**
   * {@inheritdoc}
   */
  public function contains(ContextCollectionInterface $collection) {
    return in_array($collection, $this->collections, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function recalls(string $checksum): bool {
    return in_array($checksum, $this->checksums, TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata(string $checksum): ?CacheableMetadata {
    if (isset($this->collectionsByChecksum[$checksum])) {
      return CacheableMetadata::createFromObject($this->collectionsByChecksum[$checksum]);
    }
    if (isset($this->cacheableMetadataMemory[$checksum])) {
      return $this->cacheableMetadataMemory[$checksum];
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function current(): ?ContextCollectionInterface {
    $collection = end($this->collections);
    return $collection ?: NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $cache_contexts = parent::getCacheContexts();
    $cache_context_is_avalable = $this->contextStackCacheContextIsAvailable();
    if ($this->hasPurpose() && $cache_context_is_avalable) {
      $cache_context_parameterized = 'context_stack:' . $this->getPurpose() . ':' . $this->generateChecksum();
      if (!in_array($cache_context_parameterized, $cache_contexts, TRUE)) {
        $cache_contexts[] = $cache_context_parameterized;
      }
    }

    // Make sure that when not given otherwise, at least one cache context ID
    // regarding context stack is present.
    $has_cache_context = FALSE;
    foreach ($cache_contexts as $cache_context) {
      if (substr($cache_context, 0, 13) === 'context_stack') {
        $has_cache_context = TRUE;
        break;
      }
    }
    if (!$has_cache_context && $cache_context_is_avalable) {
      $cache_contexts[] = 'context_stack';
    }

    return $cache_contexts;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    if (!$this->hasPurpose()) {
      // We cannot make any cache assumptions about contexts without a purpose.
      return 0;
    }
    return parent::getCacheMaxAge();
  }

  /**
   * {@inheritdoc}
   *
   * The invokation of this method always creates a new context collection,
   * and pushes the newly created collection to this stack.
   */
  public function addContext(ContextInterface $context, ?string $id = NULL): ContextCollectionInterface {
    $collection = $this->getContextStackFactory()->createCollection();
    $collection->addContext($context, $id);
    $this->push($collection);
    return $collection;
  }

  /**
   * {@inheritdoc}
   *
   * Looks up the stack until it finds the first context.
   */
  public function hasContext(string $id): bool {
    if (empty($this->collections)) {
      return FALSE;
    }
    $collection = end($this->collections);
    if ($collection->hasContext($id)) {
      return TRUE;
    }
    while ($collection = prev($this->collections)) {
      if ($collection->hasContext($id)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   *
   * Looks up the stack until it finds the first context.
   */
  public function getContext(string $id): ?ContextInterface {
    if (empty($this->collections)) {
      return NULL;
    }
    $collection = end($this->collections);
    if ($context = $collection->getContext($id)) {
      return $context;
    }
    while ($collection = prev($this->collections)) {
      if ($context = $collection->getContext($id)) {
        return $context;
      }
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getPurpose(): ?string {
    return $this->purpose ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getScope(): array {
    if (empty($this->collections)) {
      return [];
    }

    $collection = end($this->collections);
    $scope = $collection->getScope();
    while ($collection = prev($this->collections)) {
      $scope += $collection->getScope();
    }
    return $scope;
  }

  /**
   * {@inheritdoc}
   *
   * Includes every existent context of this stack.
   */
  public function getAncillaries(): array {
    $all_contexts = [];
    reset($this->collections);
    foreach ($this->collections as $collection) {
      foreach ($collection->getAncillaries() as $context) {
        if (!in_array($context, $all_contexts, TRUE)) {
          $all_contexts[] = $context;
        }
      }
    }
    return $all_contexts;
  }

  /**
   * {@inheritdoc}
   */
  public function getNestingLevel(): int {
    return $this->nestingLevel;
  }

  /**
   * {@inheritdoc}
   */
  public function generateChecksum(): string {
    // We allow checksum generation without an exception, because this stack
    // is actually part of a stack, which is this stack by itself.
    return $this->doGenerateChecksum();
  }

  /**
   * {@inheritdoc}
   */
  public function pushed(ContextCollectionInterface $collection): void {
    if (!($this === $collection)) {
      return;
    }
    if ($current = $this->current()) {
      $current->pushed($current);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function popped(ContextCollectionInterface $collection): void {
    if (!($this === $collection)) {
      return;
    }
    if ($current = $this->current()) {
      $current->popped($current);
    }
  }

  /**
   * Implements the magic sleep method.
   */
  public function __sleep() {
    $vars = parent::__sleep();
    return array_filter($vars, function ($value) {
      return !in_array($value,
        [
          'checksums',
          'collectionsByChecksum',
          'cacheableMetadataMemory',
          'nestingLevel',
        ]);
    });
  }

  /**
   * Implements the magic sleep method.
   */
  public function __wakeup() {
    parent::__wakeup();
    $collections = $this->collections ?? [];
    reset($collections);
    $this->collections = [];
    $this->checksums = [];
    $this->collectionsByChecksum = [];
    $this->cacheableMetadataMemory = [];
    $this->nestingLevel = 0;
    foreach ($collections as $collection) {
      $this->push($collection);
    }
  }

}
