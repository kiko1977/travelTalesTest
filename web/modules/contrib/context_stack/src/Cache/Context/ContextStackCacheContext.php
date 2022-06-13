<?php

namespace Drupal\context_stack\Cache\Context;

use Drupal\context_stack\ContextStackTrait;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;

/**
 * The ContextStackCacheContext service, for "per context stack" caching.
 *
 * This cache context may vary a lot, as the state of the stack can change
 * multiple times at runtime.
 *
 * Cache context ID: 'context_stack'.
 */
class ContextStackCacheContext implements CacheContextInterface {

  use ContextStackTrait;

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Context stack');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(?string $param = NULL) {
    if (is_null($param) || $param === '') {
      $checksums = '';
      foreach ($this->getContextStackRepository()->getAll() as $context_stack) {
        $checksums .= $context_stack->generateChecksum();
      }
      return hash('md4', $checksums);
    }
    list($purpose, $checksum) = array_pad(explode(':', (string) $param, 2), 2, NULL);
    if (!($context_stack = $this->getContextStack($purpose))) {
      return $this->getContext(NULL);
    }
    if (is_null($checksum) || !$context_stack->recalls($checksum)) {
      return $context_stack->generateChecksum();
    }
    return $checksum;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata(?string $param = NULL) {
    $metadata = new CacheableMetadata();
    if (is_null($param) || $param === '') {
      foreach ($this->getContextStackRepository()->getAll() as $context_stack) {
        $metadata->addCacheableDependency($context_stack);
      }
      return $metadata;
    }
    list($purpose, $checksum) = array_pad(explode(':', (string) $param, 2), 2, NULL);
    if (!($context_stack = $this->getContextStack($purpose))) {
      // We cannot make any cache assumptions about anonymous context stacks.
      $metadata->mergeCacheMaxAge(0);
      return $metadata;
    }
    if (!is_null($checksum) && $context_stack->recalls($checksum) && ($stack_metadata = $context_stack->getCacheableMetadata($checksum))) {
      return $metadata->addCacheableDependency($stack_metadata);
    }
    return $metadata->addCacheableDependency($context_stack);
  }

}
