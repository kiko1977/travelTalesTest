<?php

namespace Drupal\context_stack_test\Controller;

use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * A controller for returning a redirect response.
 */
class RedirectController {

  /**
   * Returns a redirect response.
   */
  public function redirect(): RedirectResponse {
    return new RedirectResponse(Url::fromRoute('context_stack_test.empty')->toString());
  }

}
