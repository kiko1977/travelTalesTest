<?php

namespace Drupal\context_stack\Plugin\Context;

use Drupal\Core\Plugin\Context\ContextInterface;

/**
 * Interface for contexts that can change their context values on runtime.
 *
 * Context plugins that implement this interface cannot be pushed to a context
 * stack, because pushed contexts may not change their internal states.
 */
interface RuntimeChangingContextInterface extends ContextInterface {}
