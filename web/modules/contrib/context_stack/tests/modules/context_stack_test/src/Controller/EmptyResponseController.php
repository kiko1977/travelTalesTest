<?php

namespace Drupal\context_stack_test\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * A controller for returning an empty response.
 */
class EmptyResponseController {

  /**
   * Returns an empty response.
   */
  public function empty(): Response {
    return new Response('', 200);
  }

}
