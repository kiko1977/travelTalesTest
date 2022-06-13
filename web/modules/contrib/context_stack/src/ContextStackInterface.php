<?php

namespace Drupal\context_stack;

use Drupal\Core\Cache\CacheableMetadata;

/**
 * Interface for context stack implementations.
 */
interface ContextStackInterface extends ContextCollectionInterface {

  /**
   * Adds the given context collection to the stack.
   *
   * @param \Drupal\context_stack\ContextCollectionInterface $collection
   *   The context collection to add.
   *
   * @return \Drupal\context_stack\ContextStackInterface
   *   The context stack where the collection was added to.
   *
   * @throws \RuntimeException
   *   When the nesting level is too high, a runtime exception will be thrown
   *   to avoid excessive resource consumption.
   */
  public function push(ContextCollectionInterface $collection): ContextStackInterface;

  /**
   * Removes the last added context collection from the stack.
   *
   * @return \Drupal\context_stack\ContextCollectionInterface|null
   *   The current context collection, or NULL if no context was initialized.
   */
  public function pop(): ?ContextCollectionInterface;

  /**
   * Lookup whether the given collection is part of this stack.
   *
   * @param \Drupal\context_stack\ContextCollectionInterface $collection
   *   The collection to check for.
   */
  public function contains(ContextCollectionInterface $collection);

  /**
   * Get the current context collection.
   *
   * @return \Drupal\context_stack\ContextCollectionInterface|null
   *   The current collection, or NULL if no collection exists at all.
   */
  public function current(): ?ContextCollectionInterface;

  /**
   * Get the current nesting level.
   *
   * The nesting level is not usable as an identifier for context collections.
   * A collection may be popped from the stack, and a new one pushed to the
   * stack afterwards. Both would then share the same nesting level.
   *
   * @return int
   *   The nesting level. Returns 0 when this stack contains no collection.
   */
  public function getNestingLevel(): int;

  /**
   * Whether this stack recalls a collection that has (or had) the checksum.
   *
   * @param string $checksum
   *   The collection checksum to check for.
   *
   * @return bool
   *   Returns TRUE if it recalls, FALSE otherwise.
   */
  public function recalls(string $checksum): bool;

  /**
   * Returns known cacheable metadata for a collection that has the checksum.
   *
   * @param string $checksum
   *   The collection checksum to get the cacheable metadata for.
   *
   * @return \Drupal\Core\Cache\CacheableMetadata|null
   *   The cacheable metadata, or NULL if not found.
   */
  public function getCacheableMetadata(string $checksum): ?CacheableMetadata;

}
