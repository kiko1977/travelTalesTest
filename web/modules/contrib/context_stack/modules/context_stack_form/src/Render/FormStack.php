<?php

namespace Drupal\context_stack_form\Render;

use Drupal\context_stack\ContextCollectionInterface;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Trusted callbacks for pushing entities into the "form" stack.
 */
final class FormStack implements TrustedCallbackInterface {

  /**
   * Holds a list of added context collections, keyed by a unique ID.
   *
   * @var \Drupal\context_stack\ContextCollectionInterface[]
   */
  private static array $collections = [];

  /**
   * Pre render callback to add form entities to the stack.
   *
   * @param array $element
   *   The render element.
   *
   * @return array
   *   The (preprocessed) render element.
   */
  public static function preRender(array $element) {
    if (!empty($element['#context_stack']['form'])) {
      $collection = $element['#context_stack']['form'];
      if ($collection instanceof ContextCollectionInterface) {
        /** @var \Drupal\context_stack\ContextStackInterface $context_stack */
        $context_stack = \Drupal::service('context_stack.form');
        if (!$collection->getParent() && $collection !== $context_stack->current()) {
          $context_stack->push($collection);
        }
        // Keep the collection outside of the render array in mind, in order to
        // avoid serialization problems, as some components might want to cache
        // the render array or pass it along multiple requests.
        $unique_id = array_search($collection, self::$collections, TRUE) ?: uniqid(mt_rand(), TRUE);
        self::$collections[$unique_id] = $collection;
        $element['#context_stack']['form'] = $unique_id;
      }
    }
    return $element;
  }

  /**
   * Post render callback to pop form entities off the stack.
   *
   * @param mixed $children
   *   The result of rendering the children.
   * @param mixed $element
   *   The element as render array.
   *
   * @return mixed
   *   The post-processed result.
   */
  public static function postRender($children, $element) {
    if (isset($element['#context_stack']['form'])) {
      $collection = $element['#context_stack']['form'];
      if (is_scalar($collection) && isset(self::$collections[$collection])) {
        $unique_id = $collection;
        $collection = self::$collections[$unique_id];
        unset(self::$collections[$unique_id]);
      }
      if ($collection instanceof ContextCollectionInterface) {
        /** @var \Drupal\context_stack\ContextStackInterface $context_stack */
        $context_stack = \Drupal::service('context_stack.form');
        while (($collection !== $context_stack->pop()) && $context_stack->current());
      }
    }
    return $children;
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks() {
    return ['preRender', 'postRender'];
  }

}
