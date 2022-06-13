<?php

namespace Drupal\context_stack\Plugin\Context;

/**
 * Context for a current parent entity in scope defined by a context stack.
 */
class CurrentParentEntityContext extends CurrentEntityContext {

  /**
   * {@inheritdoc}
   */
  public function getContextData() {
    foreach ($this->contextStacks as $stack) {
      $id = $this->id;
      if ($collection = $stack->current()) {
        if ($current_context = $collection->getContext($id)) {
          while ($collection = $collection->getParent()) {
            if (!($parent_context = $collection->getContext($id))) {
              break;
            }
            if ($parent_context !== $current_context) {
              return $parent_context->getContextData();
            }
          }
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
        if ($current_context = $collection->getContext($id)) {
          while ($collection = $collection->getParent()) {
            if (!($parent_context = $collection->getContext($id))) {
              break;
            }
            if ($parent_context !== $current_context) {
              return $parent_context->getContextValue();
            }
          }
        }
      }
    }
    return GenericEntityContext::getContextValue();
  }

}
