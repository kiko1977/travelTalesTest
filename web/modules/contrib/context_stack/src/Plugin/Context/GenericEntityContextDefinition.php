<?php

namespace Drupal\context_stack\Plugin\Context;

use Drupal\Core\Plugin\Context\ContextDefinition;
use Drupal\Core\Plugin\Context\EntityContextDefinition;

/**
 * Default implementation for a generic entity context definition.
 *
 * The main difference to EntityContextDefinition is, that a generic entity
 * context definition may be un-opinionated regards the entity type.
 */
class GenericEntityContextDefinition extends EntityContextDefinition {

  /**
   * {@inheritdoc}
   */
  public function __construct($data_type = 'entity', $label = NULL, $required = TRUE, $multiple = FALSE, $description = NULL, $default_value = NULL) {
    if ($data_type !== 'entity' && strpos($data_type, 'entity:') !== 0) {
      $data_type = 'entity:' . $data_type;
    }
    ContextDefinition::__construct($data_type, $label, $required, $multiple, $description, $default_value);
  }

  /**
   * {@inheritdoc}
   */
  public static function create($data_type = 'entity') {
    return new static($data_type);
  }

  /**
   * Returns the entity type ID of this context.
   *
   * @return string
   *   The entity type ID, or an empty string if not specified.
   */
  protected function getEntityTypeId() {
    if (strpos($this->getDataType(), 'entity:') !== 0) {
      return '';
    }
    return parent::getEntityTypeId();
  }

  /**
   * {@inheritdoc}
   */
  protected function getConstraintObjects() {
    if (strpos($this->getDataType(), 'entity:') !== 0) {
      return ContextDefinition::getConstraintObjects();
    }
    return parent::getConstraintObjects();
  }

  /**
   * {@inheritdoc}
   */
  protected function getSampleValues() {
    if ($this->getEntityTypeId()) {
      return parent::getSampleValues();
    }
    // Cannot generate any sample values if the entity type is not known.
    return [];
  }

}
