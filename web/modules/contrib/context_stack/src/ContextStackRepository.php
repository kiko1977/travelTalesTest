<?php

namespace Drupal\context_stack;

/**
 * Default implementation of a context stack repository.
 */
class ContextStackRepository implements ContextStackRepositoryInterface {

  /**
   * All known context stacks.
   *
   * @var \Drupal\context_stack\ContextStackInterface[]
   */
  protected array $stacks = [];

  /**
   * Get the context stack repository service.
   *
   * @return \Drupal\context_stack\ContextStackRepositoryInterface
   *   The repository service.
   */
  public static function getService(): ContextStackRepositoryInterface {
    return \Drupal::service('context_stack.repository');
  }

  /**
   * {@inheritdoc}
   */
  public function getAll(): array {
    return $this->stacks;
  }

  /**
   * {@inheritdoc}
   */
  public function get(string $purpose): ?ContextStackInterface {
    return $this->stacks[$purpose] ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function addStack(ContextStackInterface $stack): void {
    if (!in_array($stack, $this->stacks, TRUE)) {
      if (!$stack->hasPurpose()) {
        $this->stacks[] = $stack;
      }
      else {
        $this->stacks[$stack->getPurpose()] = $stack;
      }
    }
  }

}
