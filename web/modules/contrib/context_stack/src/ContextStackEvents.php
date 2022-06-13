<?php

namespace Drupal\context_stack;

/**
 * Defines events provided by the Context Stack module.
 */
final class ContextStackEvents {

  /**
   * Dispatched before a collection is pushed to the stack.
   *
   * This event allows modules to act on collections before they are being
   * pushed to the stack.
   *
   * @Event
   *
   * @var string
   */
  const PUSH_BEFORE = 'context_stack.push.before';

  /**
   * Dispatched after a collection got pushed to the stack.
   *
   * This event allows modules to act on collections after they where pushed to
   * the stack.
   *
   * @Event
   *
   * @var string
   */
  const PUSH_AFTER = 'context_stack.push.after';

  /**
   * Dispatched before a collection is popped off the stack.
   *
   * This event allows modules to act on collections before they are being
   * popped off the stack.
   *
   * @Event
   *
   * @var string
   */
  const POP_BEFORE = 'context_stack.pop.before';

  /**
   * Dispatched after a collection got popped off the stack.
   *
   * This event allows modules to act on collections after they where popped off
   * the stack.
   *
   * @Event
   *
   * @var string
   */
  const POP_AFTER = 'context_stack.pop.after';

}
