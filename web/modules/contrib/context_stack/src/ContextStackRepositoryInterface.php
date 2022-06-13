<?php

namespace Drupal\context_stack;

/**
 * Interface for context stack repositories.
 */
interface ContextStackRepositoryInterface {

  /**
   * Get all known context stacks.
   *
   * @return \Drupal\context_stack\ContextStackInterface[]
   *   All known context stacks, keyed by purpose or numerically if no purpose
   *   was defined.
   */
  public function getAll(): array;

  /**
   * Get a context stack for the given purpose.
   *
   * @param string $purpose
   *   The purpose, e.g. "view".
   *
   * @return \Drupal\context_stack\ContextStackInterface|null
   *   The found context stack, or NULL if not found.
   */
  public function get(string $purpose): ?ContextStackInterface;

  /**
   * Adds a context stack to the repository.
   *
   * @param \Drupal\context_stack\ContextStackInterface $stack
   *   The context stack.
   */
  public function addStack(ContextStackInterface $stack): void;

}
