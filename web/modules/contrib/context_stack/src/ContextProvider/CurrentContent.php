<?php

namespace Drupal\context_stack\ContextProvider;

use Drupal\context_stack\ContextStackInterface;
use Drupal\context_stack\Plugin\Context\CurrentEntityContext;
use Drupal\context_stack\Plugin\Context\GenericEntityContext;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Sets current content entity contexts in scope defined by a context stack.
 */
class CurrentContent extends CurrentContentBase {

  /**
   * {@inheritdoc}
   */
  protected function getRuntimeContext(EntityTypeInterface $entity_type, ContextStackInterface $context_stack, string $context_id): ?CurrentEntityContext {
    $context = CurrentEntityContext::fromEntityType($entity_type, $this->t('@name @label in "@purpose" scope', [
      '@name' => $this->t("Current"),
      '@label' => $entity_type->getLabel(),
      '@purpose' => $this->t($context_stack->getPurpose()),
    ]), $context_stack, $context_id);
    return $context_stack->validate(GenericEntityContext::fromEntityType($entity_type), FALSE) ? $context : NULL;
  }

  /**
   * {@inheritdoc}
   */
  protected function getGenericRuntimeContext(ContextStackInterface $context_stack, string $context_id): ?CurrentEntityContext {
    $context = CurrentEntityContext::fromNothing($this->t('@name entity in "@purpose" scope', [
      '@name' => $this->t("Current"),
      '@purpose' => $this->t($context_stack->getPurpose()),
    ]), $context_stack, $context_id);
    return $context_stack->validate(GenericEntityContext::fromNothing(), FALSE) ? $context : NULL;
  }

}
