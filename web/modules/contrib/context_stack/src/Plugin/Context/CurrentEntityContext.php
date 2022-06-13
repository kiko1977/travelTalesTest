<?php

namespace Drupal\context_stack\Plugin\Context;

use Drupal\context_stack\ContextStackInterface;
use Drupal\context_stack\ContextStackTrait;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Plugin\Context\ContextDefinitionInterface;

/**
 * Context for a current entity in scope defined by a context stack.
 */
class CurrentEntityContext extends GenericEntityContext implements RuntimeChangingContextInterface {

  use ContextStackTrait;

  /**
   * The context ID.
   *
   * @var string
   */
  protected string $id;

  /**
   * Creates a new CurrentEntityContext object.
   *
   * @param \Drupal\Core\Plugin\Context\ContextDefinitionInterface $context_definition
   *   The context definition.
   * @param mixed|null $context_value
   *   The context value object.
   * @param \Drupal\context_stack\ContextStackInterface|null $context_stack
   *   The according context stack. When not given here, the stack should be
   *   passed via ::addContextStack() method right after instantiation.
   * @param string|null $context_id
   *   The context ID to use for retrieving the current value from the stack.
   *   When not set, the data type (entity type ID or "entity") will be used.
   */
  public function __construct(ContextDefinitionInterface $context_definition, $context_value = NULL, ?ContextStackInterface $context_stack = NULL, ?string $context_id = NULL) {
    parent::__construct($context_definition, $context_value);
    if (isset($context_stack)) {
      $this->addContextStack($context_stack);
    }
    if (is_null($context_id)) {
      $data_type_parts = explode(':', $context_definition->getDataType());
      if (reset($data_type_parts) !== 'entity') {
        throw new \InvalidArgumentException("Data other than entities is not supported by CurrentEntityContext.");
      }
      $context_id = end($data_type_parts);
    }
    $this->id = $context_id;
  }

  /**
   * Creates the context using the given context ID.
   *
   * @param string $id
   *   The context ID, without service name prefix, for example "node".
   * @param \Drupal\context_stack\ContextStackInterface|null $context_stack
   *   The according context stack. When not given here, the stack should be
   *   passed via ::addContextStack() method right after instantiation.
   *
   * @return static
   *   The instantiated context.
   */
  public static function fromContextId(string $id, ?ContextStackInterface $context_stack = NULL): CurrentEntityContext {
    return new static(GenericEntityContextDefinition::create(), NULL, $context_stack, $id);
  }

  /**
   * {@inheritdoc}
   */
  public static function fromEntity(EntityInterface $entity, $label = NULL, ?ContextStackInterface $context_stack = NULL, ?string $context_id = NULL) {
    $definition = GenericEntityContextDefinition::create('entity:' . $entity->getEntityTypeId());
    if ($label) {
      $definition->setLabel($label);
    }
    return new static($definition, $entity, $context_stack, $context_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function fromEntityType(EntityTypeInterface $entity_type, $label = NULL, ?ContextStackInterface $context_stack = NULL, ?string $context_id = NULL) {
    $definition = GenericEntityContextDefinition::fromEntityType($entity_type);
    if ($label) {
      $definition->setLabel($label);
    }
    return new static($definition, NULL, $context_stack, $context_id);
  }

  /**
   * {@inheritdoc}
   */
  public static function fromNothing($label = NULL, ?ContextStackInterface $context_stack = NULL, ?string $context_id = NULL): GenericEntityContext {
    $definition = GenericEntityContextDefinition::create();
    if ($label) {
      $definition->setLabel($label);
    }
    return new static($definition, NULL, $context_stack, $context_id);
  }

  /**
   * {@inheritdoc}
   */
  public function getContextData() {
    foreach ($this->contextStacks as $context_stack) {
      if ($context = $context_stack->getContext($this->id)) {
        return $context->getContextData();
      }
    }
    return parent::getContextData();
  }

  /**
   * {@inheritdoc}
   */
  public function getContextValue() {
    foreach ($this->contextStacks as $context_stack) {
      if ($context = $context_stack->getContext($this->id)) {
        return $context->getContextValue();
      }
    }
    return parent::getContextValue();
  }

}
