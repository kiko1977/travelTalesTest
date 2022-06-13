<?php

namespace Drupal\context_stack;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Plugin\Context\ContextInterface;

/**
 * Interface for context collections.
 */
interface ContextCollectionInterface extends ContextSwitchInterface, RefinableCacheableDependencyInterface {

  /**
   * Adds the given context holding a context value to the current collection.
   *
   * @param \Drupal\Core\Plugin\Context\ContextInterface $context
   *   The context instance, which must hold a value.
   * @param string|null $id
   *   (Optional) Set an ID for accessing the context afterwards. By default,
   *   the data type without prefix of the given context value will be used. For
   *   example, when the data type is set as "entity:node", "node" will be used.
   *
   * @return \Drupal\context_stack\ContextCollectionInterface
   *   The collection where the given context was added to.
   *
   * @throws \RuntimeException
   *   Either when
   *   - A context already exists in the collection, having the same data type
   *     or context ID.
   *   - The context collection was already pushed to a stack.
   *   - The given context does not hold any value (empty contexts are not
   *     supported).
   *   - The context fails validation on the given list of constraints (if any).
   * @throws \LogicException
   *   Either when
   *   - The $id is not given and the data type is empty at the same time.
   *   - The given context is an instance of
   *     \Drupal\context_stack\RuntimeChangingContextInterface.
   */
  public function addContext(ContextInterface $context, ?string $id = NULL): ContextCollectionInterface;

  /**
   * Whether context exists for the given ID.
   *
   * Looks up superordinate contexts.
   *
   * @param string $id
   *   The ID to check for, e.g. "node".
   *
   * @return bool
   *   Returns TRUE if it exists, FALSE otherwise.
   */
  public function hasContext(string $id): bool;

  /**
   * Get a context by ID.
   *
   * Looks up superordinate contexts and chooses the first found context.
   *
   * @param string $id
   *   The ID, for example "node".
   *
   * @return \Drupal\Core\Plugin\Context\ContextInterface|null
   *   The context, or NULL if not available.
   */
  public function getContext(string $id) : ?ContextInterface;

  /**
   * Validates given context whether it may be of interest for this collection.
   *
   * This method may be helpful in situations where a collection is given, but
   * despite of a defined purpose it's not known what the collection is being
   * used for. This method may help in such situations, to decide whether it
   * might make sense to add the context to this collection or not.
   *
   * @param \Drupal\Core\Plugin\Context\ContextInterface $context
   *   The context instance to validate.
   * @param bool $check_emptyness
   *   Whether to include a check if the context has no value. Default is TRUE.
   *   This may be set to FALSE to check whether the contained data type would
   *   pass, in case the context would have a value later on.
   *
   * @return bool
   *   Returns TRUE if it may be relevant, FALSE otherwise.
   */
  public function validate(ContextInterface $context, bool $check_emptyness = TRUE): bool;

  /**
   * Adds a validation constraint.
   *
   * @param string $constraint_name
   *   The name of the constraint to add, i.e. its plugin id.
   * @param mixed $options
   *   The constraint options as required by the constraint plugin, or NULL.
   *
   * @return $this
   */
  public function addConstraint($constraint_name, $options = NULL): ContextCollectionInterface;

  /**
   * Whether this collection has a purpose specified.
   *
   * @return bool
   *   Returns TRUE if it has a purpose, FALSE otherwise.
   */
  public function hasPurpose(): bool;

  /**
   * Get the purpose of this stack, if defined.
   *
   * @return string|null
   *   The purpose, or NULL if not defined.
   */
  public function getPurpose(): ?string;

  /**
   * Returns all currently existent contexts in scope.
   *
   * Includes every existent context from the current collection plus every
   * superordinate context. A superordinate context is the closest ancestor
   * context found above the current collection, which is not set with an ID
   * within the current collection.
   *
   * @return \Drupal\Core\Plugin\Context\ContextInterface[]
   *   All contexts in scope (may be empty). Keys are (unqualified) context IDs.
   */
  public function getScope(): array;

  /**
   * Returns all currently existent ancillary contexts.
   *
   * @return \Drupal\Core\Plugin\Context\ContextInterface[]
   *   All ancillary contexts (may be empty). The array holds every context
   *   instance only once. This is not an associative array, because multiple
   *   contexts may exists for the same ID, and one context object may be used
   *   for multiple IDs at the same time.
   */
  public function getAncillaries(): array;

  /**
   * Get the parent collection (if any).
   *
   * @return \Drupal\context_stack\ContextCollectionInterface
   *   The parent collection, or NULL if not existent.
   */
  public function getParent(): ?ContextCollectionInterface;

  /**
   * Sets the according parent collection right after it got pushed to a stack.
   *
   * @param \Drupal\context_stack\ContextCollectionInterface|null $parent
   *   The parent to set, or NULL if this collection is the root of the stack.
   * @param \Drupal\context_stack\ContextStackInterface $stack
   *   The according stack that holds both the parent and this collection.
   *
   * @throws \LogicException
   *   Either when
   *   - The stack does not contain this collection item as current collection.
   *   - A parent was already set before and that existing one is not the same
   *     as the given argument.
   */
  public function setParent(?ContextCollectionInterface $parent, ContextStackInterface $stack): void;

  /**
   * Generates a checksum hash of the context collection.
   *
   * The checksum may only be generated when the collection is part of a stack.
   *
   * @return string
   *   The checksum.
   *
   * @throws \LogicException
   *   When this collection is not part of a stack.
   */
  public function generateChecksum(): string;

}
