<?php

namespace Drupal\context_stack\ContextProvider;

use Drupal\context_stack\ContextStackInterface;
use Drupal\context_stack\ContextStackTrait;
use Drupal\context_stack\Plugin\Context\CurrentEntityContext;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Context\ContextProviderInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Base class for current content entity contexts in scope of a stack.
 */
abstract class CurrentContentBase implements ContextProviderInterface {

  use ContextStackTrait;
  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs a new CurrentEntityContextProvider service.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getRuntimeContexts(array $unqualified_context_ids) {
    $result = [];
    $by_stack = [];
    if (empty($unqualified_context_ids)) {
      $entity_type_ids = array_keys($this->entityTypeManager->getDefinitions());
      foreach ($this->getContextStackRepository()->getAll() as $context_stack) {
        if (!$context_stack->hasPurpose()) {
          // Only support context stacks with a meaningful purpose.
          continue;
        }
        $purpose = $context_stack->getPurpose();
        $by_stack[$purpose] = $entity_type_ids;
        $by_stack[$purpose][] = 'entity';
      }
    }
    else {
      foreach ($unqualified_context_ids as $unqualified_context_id) {
        list($purpose, $context_id) = explode('.', $unqualified_context_id, 2);
        if (!($context_stack = $this->getContextStack($purpose))) {
          continue;
        }
        $by_stack[$purpose][] = $context_id;
      }
    }
    foreach ($by_stack as $purpose => $context_ids) {
      $context_stack = $this->getContextStack($purpose);
      foreach ($context_ids as $context_id) {
        if ($entity_type = $this->entityTypeManager->getDefinition($context_id, FALSE)) {
          if ($entity_type->entityClassImplements(ContentEntityInterface::class)) {
            if ($context = $this->getRuntimeContext($entity_type, $context_stack, $context_id)) {
              $result[$purpose . '.' . $context_id] = $context;
            }
          }
        }
        elseif ($context = $this->getGenericRuntimeContext($context_stack, $context_id)) {
          $result[$purpose . '.' . $context_id] = $context;
        }
      }
    }
    return $result;
  }

  /**
   * Get the runtime context for the given entity type.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   * @param \Drupal\context_stack\ContextStackInterface $context_stack
   *   The context stack to define the context for.
   * @param string $context_id
   *   The context ID for identifying the defined context in the context stack.
   *
   * @return \Drupal\context_stack\Plugin\Context\CurrentEntityContext|null
   *   An instance of CurrentEntityContext, or NULL if no context is defined.
   */
  abstract protected function getRuntimeContext(EntityTypeInterface $entity_type, ContextStackInterface $context_stack, string $context_id): ?CurrentEntityContext;

  /**
   * Get the runtime context for a generic entity.
   *
   * @param \Drupal\context_stack\ContextStackInterface $context_stack
   *   The context stack to define the context for.
   * @param string $context_id
   *   The context ID for identifying the defined context in the context stack.
   *
   * @return \Drupal\context_stack\Plugin\Context\CurrentEntityContext|null
   *   An instance of CurrentEntityContext, or NULL if no context is defined.
   */
  abstract protected function getGenericRuntimeContext(ContextStackInterface $context_stack, string $context_id): ?CurrentEntityContext;

  /**
   * {@inheritdoc}
   */
  public function getAvailableContexts() {
    return $this->getRuntimeContexts([]);
  }

}
