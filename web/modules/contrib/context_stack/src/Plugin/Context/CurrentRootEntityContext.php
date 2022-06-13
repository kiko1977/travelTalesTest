<?php

namespace Drupal\context_stack\Plugin\Context;

/**
 * Context for a current root entity in scope defined by a context stack.
 */
class CurrentRootEntityContext extends CurrentEntityContext {

  /**
   * {@inheritdoc}
   */
  public function getContextData() {
    foreach ($this->contextStacks as $stack) {
      $id = $this->id;
      if ($collection = $stack->current()) {
        if ($context = $collection->getContext($id)) {
          while ($collection = $collection->getParent()) {
            if ($parent_context = $collection->getContext($id)) {
              $context = $parent_context;
            }
            else {
              break;
            }
          }
          return $context->getContextData();
        }
      }
    }
    return GenericEntityContext::getContextData();
  }

  /**
   * {@inheritdoc}
   */
  public function getContextValue() {
    foreach ($this->contextStacks as $stack) {
      $id = $this->id;
      if ($collection = $stack->current()) {
        if ($context = $collection->getContext($id)) {
          while ($collection = $collection->getParent()) {
            if ($parent_context = $collection->getContext($id)) {
              $context = $parent_context;
            }
            else {
              break;
            }
          }
          return $context->getContextValue();
        }
      }
    }
    return GenericEntityContext::getContextValue();
  }

}
