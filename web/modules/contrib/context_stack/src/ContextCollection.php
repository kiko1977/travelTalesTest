<?php

namespace Drupal\context_stack;

use Drupal\context_stack\Plugin\Context\RuntimeChangingContextInterface;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Plugin\Validation\Constraint\EntityTypeConstraint;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Plugin\Context\ContextInterface;
use Drupal\Core\Plugin\Context\EntityContext;

/**
 * Default context collection implementation.
 */
class ContextCollection implements ContextCollectionInterface {

  use RefinableCacheableDependencyTrait;

  use DependencySerializationTrait {
    __sleep as goodNightServices;
    __wakeup as goodMorningServices;
  }

  /**
   * Whether the parent was set.
   *
   * @var bool
   */
  protected $parentSet = FALSE;

  /**
   * The according collection of context items.
   *
   * @var \Drupal\Core\Plugin\Context\ContextInterface[]
   */
  protected array $contexts = [];

  /**
   * Optional reference to the parent context collection.
   *
   * @var \Drupal\context_stack\ContextCollectionInterface|null
   */
  protected ?ContextCollectionInterface $parent;

  /**
   * Optional reference to the according context stack.
   *
   * @var \Drupal\context_stack\ContextStackInterface|null
   */
  protected ?ContextStackInterface $stack;

  /**
   * A list of validation constraints.
   *
   * @var \Symfony\Component\Validator\Constraint[]
   */
  protected array $constraints = [];

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $cache = (new CacheableMetadata())->addCacheContexts($this->cacheContexts);
    if ($this->parentSet && $this->stack->hasPurpose() && $this->contextStackCacheContextIsAvailable()) {
      $cache_context_parameterized = 'context_stack:' . $this->stack->getPurpose() . ':' . $this->generateChecksum();
      $cache->addCacheContexts([$cache_context_parameterized]);
    }
    foreach ($this->getScope() as $context) {
      $cache = $cache->addCacheContexts($context->getCacheContexts());
      $values = $context->getContextValue();
      if (!$context->getContextDefinition()->isMultiple()) {
        $values = [$values];
      }
      foreach ($values as $value) {
        if ($value instanceof CacheableDependencyInterface) {
          $cache->addCacheContexts($value->getCacheContexts());
        }
      }
    }
    return array_unique($cache->getCacheContexts());
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    if ($this->parentSet && !$this->stack->hasPurpose()) {
      // We cannot make any cache assumptions about stacks without a purpose.
      return 0;
    }
    if (!$this->contextStackCacheContextIsAvailable()) {
      // We cannot apply proper caching when the context stack is not available
      // anymore as cache context.
      return 0;
    }
    $cache = (new CacheableMetadata())->setCacheMaxAge($this->cacheMaxAge);
    foreach ($this->getScope() as $context) {
      $cache = $cache->mergeCacheMaxAge($context->getCacheMaxAge());
      $values = $context->getContextValue();
      if (!$context->getContextDefinition()->isMultiple()) {
        $values = [$values];
      }
      foreach ($values as $value) {
        if ($value instanceof EntityInterface && $value->isNew()) {
          // Cannot cache for new entities.
          $cache->setCacheMaxAge(0);
          break;
        }
        if ($value instanceof CacheableDependencyInterface) {
          $cache->mergeCacheMaxAge($value->getCacheMaxAge());
        }
      }
    }

    return $cache->getCacheMaxAge();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $cache = (new CacheableMetadata())->addCacheTags($this->cacheTags);
    foreach ($this->getScope() as $context) {
      $cache = $cache->addCacheTags($context->getCacheTags());
      $values = $context->getContextValue();
      if (!$context->getContextDefinition()->isMultiple()) {
        $values = [$values];
      }
      foreach ($values as $value) {
        if ($value instanceof CacheableDependencyInterface) {
          $cache->addCacheTags($value->getCacheTags());
        }
      }
    }
    return $cache->getCacheTags();
  }

  /**
   * {@inheritdoc}
   */
  public function addContext(ContextInterface $context, ?string $id = NULL): ContextCollectionInterface {
    if ($this->parentSet) {
      throw new \RuntimeException("Failed attempt to add context to a collection that already got pushed to a stack. Context may only be added before pushing to the stack.");
    }
    if ($context instanceof RuntimeChangingContextInterface) {
      throw new \LogicException("Cannot add a context that might change its value on runtime.");
    }
    $definition = $context->getContextDefinition();
    if (!$context->hasContextValue()) {
      throw new \RuntimeException(sprintf("Failed attempt to pass an empty context '%s'. Only context that is not empty is allowed.", $definition->getLabel()));
    }
    if (!$id) {
      $parts = explode(':', $definition->getDataType());
      $id = end($parts);
      if (empty($parts) || !$id) {
        throw new \LogicException(sprintf("No ID could be determined from the data type of context '%s'.", $definition->getLabel()));
      }
    }
    if (isset($this->contexts[$id])) {
      throw new \RuntimeException(sprintf("Failed attempt to set an already existing context '%s' using '%s'. A context may only be set once within the current collection.", $id, $definition->getLabel()));
    }
    if (!empty($this->constraints) && !$this->validate($context)) {
      throw new \RuntimeException(sprintf("Failed attempt to pass a context '%s' that does not meet validation requirements.", $id));
    }
    $this->contexts[$id] = $context;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function hasContext(string $id): bool {
    if (isset($this->contexts[$id])) {
      return TRUE;
    }
    if (isset($this->parent)) {
      return $this->parent->hasContext($id);
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getContext(string $id): ?ContextInterface {
    if (isset($this->contexts[$id])) {
      return $this->contexts[$id];
    }
    if (isset($this->parent)) {
      return $this->parent->getContext($id);
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function hasPurpose(): bool {
    $purpose = $this->getPurpose();
    return !is_null($purpose) && $purpose !== '';
  }

  /**
   * {@inheritdoc}
   */
  public function getPurpose(): ?string {
    if (isset($this->stack)) {
      return $this->stack->getPurpose();
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getScope(): array {
    $scope = $this->contexts;
    if ($parent = $this->getParent()) {
      $scope += $parent->getScope();
    }
    return $scope;
  }

  /**
   * {@inheritdoc}
   */
  public function getAncillaries(): array {
    $all_contexts = [];
    foreach ($this->contexts as $context) {
      if (!in_array($context, $all_contexts, TRUE)) {
        $all_contexts[] = $context;
      }
    }
    return $all_contexts;
  }

  /**
   * {@inheritdoc}
   */
  public function getParent(): ?ContextCollectionInterface {
    return $this->parent ?? NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function setParent(?ContextCollectionInterface $parent, ContextStackInterface $stack): void {
    if ($this->parentSet && ($this->parent !== $parent || $this->stack !== $stack)) {
      throw new \LogicException("Cannot change the parent collection or stack. Create a new context collection instead.");
    }
    if ($this !== $stack->current()) {
      throw new \LogicException("Cannot assign a stack that does not hold this collection as current one.");
    }
    $this->parentSet = TRUE;
    $this->parent = $parent;
    $this->stack = $stack;
  }

  /**
   * {@inheritdoc}
   */
  public function generateChecksum(): string {
    if (!$this->parentSet) {
      throw new \LogicException('A checksum cannot be generated when the collection was not added to a stack.');
    }
    return $this->doGenerateChecksum();
  }

  /**
   * Does the main work for generating the checksum hash.
   *
   * @return string
   *   The checksum.
   */
  protected function doGenerateChecksum(): string {
    $id_string = (string) $this->getPurpose();
    foreach ($this->getScope() as $id => $context) {
      $id_string .= "$id:";
      if (!$context->hasContextValue() || !($values = $context->getContextValue())) {
        $id_string .= "_empty";
        continue;
      }
      if (!$context->getContextDefinition()->isMultiple()) {
        $values = [$values];
      }
      elseif (is_object($values) && method_exists($values, 'referencedEntities')) {
        $values = $values->referencedEntities();
      }
      foreach ($values as $value) {
        if ($value instanceof EntityInterface) {
          $id_string .= $value->getEntityTypeId() . '-' . $value->language()->getId() . '-';
          if ($value->isNew()) {
            $id_string .= '_new';
          }
          else {
            $id_string .= $value->id();
            if ($value instanceof RevisionableInterface) {
              $id_string .= '-' . $value->getRevisionId();
            }
          }
          continue;
        }
        if (is_object($value)) {
          if (method_exists($value, 'id')) {
            $id_string .= $value->id();
            continue;
          }
          if (method_exists($value, 'getId')) {
            $id_string .= $value->getId();
            continue;
          }
          if (method_exists($value, 'getString')) {
            $id_string .= $value->getString();
            continue;
          }
          if (method_exists($value, '__toString')) {
            $id_string .= (string) $value;
            continue;
          }
        }
        if (is_null($value) || is_scalar($value)) {
          $id_string .= serialize($value);
          continue;
        }
        $id_string .= serialize($value);
      }
    }
    return hash('md4', $id_string);
  }

  /**
   * {@inheritdoc}
   */
  public function validate(ContextInterface $context, bool $check_emptyness = TRUE): bool {
    if ($check_emptyness && !$context->hasContextValue()) {
      // ::addContext() does not support empty contexts.
      return FALSE;
    }
    if ($context instanceof RuntimeChangingContextInterface) {
      // Contexts which change their state on runtime are not supported.
      return FALSE;
    }
    if (isset($this->stack) && !$this->stack->validate($context, $check_emptyness)) {
      // The wrapping stack does not want this context.
      return FALSE;
    }
    if (empty($this->constraints)) {
      // No constraints were defined, thus validation passed.
      return TRUE;
    }
    else {
      // Try for a fast check. If we have only a check for an entity type here,
      // we can quickly lookup the type. In any other case, typed data manager
      // to the rescue below.
      $sufficient_type_match = TRUE;
      foreach ($this->constraints as $constraint) {
        if (($constraint instanceof EntityTypeConstraint) && ($context instanceof EntityContext)) {
          $data_type = $context->getContextDefinition()->getDataType();
          if (!isset($constraint->type) || !(strpos($data_type, 'entity:') === 0)) {
            $sufficient_type_match = FALSE;
            break;
          }
          if ('entity:' . $constraint->type !== $data_type) {
            return FALSE;
          }
        }
        else {
          $sufficient_type_match = FALSE;
          break;
        }
      }
      if ($sufficient_type_match) {
        return TRUE;
      }
    }
    $typed_data_manager = \Drupal::typedDataManager();
    $validator = $typed_data_manager->getValidator();
    $context_data = $context->hasContextValue() ? $context->getContextData() : $typed_data_manager->create($context->getContextDefinition()->getDataDefinition());
    $violations = $validator->validate($context_data, $this->constraints);
    return !((bool) $violations->count());
  }

  /**
   * {@inheritdoc}
   */
  public function addConstraint($constraint_name, $options = NULL): ContextCollectionInterface {
    $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
    $this->constraints[$constraint_name] = $constraint_manager->create($constraint_name, $options);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function pushed(ContextCollectionInterface $collection): void {
    if (!($this === $collection)) {
      return;
    }
    foreach ($this->contexts as $context) {
      if ($context instanceof ContextSwitchInterface) {
        $context->pushed($collection);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function popped(ContextCollectionInterface $collection): void {
    if (!($this === $collection)) {
      return;
    }
    foreach ($this->contexts as $context) {
      if ($context instanceof ContextSwitchInterface) {
        $context->popped($collection);
      }
    }
  }

  /**
   * Implements the magic sleep method.
   */
  public function __sleep() {
    // Before serialization starts, enrich this collection by any known
    // cacheability metadata before popped out context values get lost.
    $this->addCacheableDependency($this);
    // We exclude the parent and stack reference, allowing this collection to be
    // added to another context stack of a future process.
    $this->parentSet = FALSE;
    return array_filter($this->goodNightServices(), function ($value) {
      return !in_array($value, ['parent', 'stack']);
    });
  }

  /**
   * Implements the magic wakeup method.
   */
  public function __wakeup() {
    if (!$this->contextStackCacheContextIsAvailable()) {
      // When the "context_stack" cache context is missing on the next process,
      // we cannot apply proper caching on this collection.
      $this->cacheContexts = [];
      $this->cacheMaxAge = 0;
    }
    $this->goodMorningServices();
  }

  /**
   * Check whether the service for "context_stack" cache contexts is available.
   *
   * @return bool
   *   Returns TRUE if available, FALSE otherwise.
   */
  protected function contextStackCacheContextIsAvailable(): bool {
    return \Drupal::hasContainer() && in_array('context_stack', \Drupal::getContainer()->getParameter('cache_contexts'));
  }

}
