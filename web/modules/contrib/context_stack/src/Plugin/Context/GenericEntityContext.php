<?php

namespace Drupal\context_stack\Plugin\Context;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Plugin\Context\EntityContext;

/**
 * Default implementation for a generic entity context.
 *
 * The main difference to EntityContext is, that a generic entity context can be
 * (but is not forced to be) un-opinionated regards the entity type.
 */
class GenericEntityContext extends EntityContext {

  /**
   * {@inheritdoc}
   */
  public static function fromEntity(EntityInterface $entity, $label = NULL) {
    $definition = GenericEntityContextDefinition::create('entity:' . $entity->getEntityTypeId());
    if ($label) {
      $definition->setLabel($label);
    }
    return new static($definition, $entity);
  }

  /**
   * {@inheritdoc}
   */
  public static function fromEntityType(EntityTypeInterface $entity_type, $label = NULL) {
    $definition = GenericEntityContextDefinition::fromEntityType($entity_type);
    if ($label) {
      $definition->setLabel($label);
    }
    return new static($definition);
  }

  /**
   * Creates a context from nothing.
   *
   * @param string $label
   *   (optional) The label of the context.
   *
   * @return \Drupal\context_stack\Plugin\Context\GenericEntityContext
   *   The context object.
   */
  public static function fromNothing($label = NULL): GenericEntityContext {
    $definition = GenericEntityContextDefinition::create();
    if ($label) {
      $definition->setLabel($label);
    }
    return new static($definition);
  }

}
