<?php

/**
 * @file
 * Tokens provided by the Context Stack module.
 */

use Drupal\context_stack\ContextStackRepository;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Render\BubbleableMetadata;

/**
 * Implements hook_token_info().
 */
function context_stack_token_info(): array {
  $info = [];
  $stack_repo = ContextStackRepository::getService();
  foreach (['current', 'parent', 'root'] as $type) {
    $info['types'][$type] = [
      'name' => t('@type context', ['@type' => t(ucfirst($type))]),
      'description' => t('Tokens containing data from @type context.', ['@type' => t($type)]),
    ];
    foreach ($stack_repo->getAll() as $stack) {
      if (!$stack->hasPurpose()) {
        continue;
      }
      $purpose = $stack->getPurpose();
      $trs = ['@type' => t($type), '@purpose' => t($purpose)];
      switch ($purpose) {

        case 'account':
          $description = t('The "account" context provides data from the @type logged in user account. Example: [@type:account:name]', $trs);
          break;

        case 'view':
          $description = t('The "view" context provides data from the @type entity that is being viewed. The entity type needs to be specified. Example: [@type:view:node:title]', $trs);
          break;

        case 'form':
          $description = t('The "form" context provides data from the @type entity of the form being displayed. The entity type needs to be specified. Example: [@type:form:node:title]', $trs);
          break;

        default:
          $description = t('Data from @type "@purpose" context.', $trs);
      }
      $info['tokens'][$type][$purpose] = [
        'name' => t('@purpose', $trs),
        'description' => $description,
        'dynamic' => TRUE,
      ];
    }
  }
  return $info;
}

/**
 * Implements hook_tokens().
 */
function context_stack_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata): array {
  $replacements = [];

  if (in_array($type, ['current', 'parent', 'root'])) {
    $service_ids = [
      'current' => 'context_stack.current_content',
      'parent' => 'context_stack.current_parent_content',
      'root' => 'context_stack.current_root_content',
    ];
    foreach ($tokens as $name => $original) {
      $parts = explode(':', $name);
      $purpose = array_shift($parts);
      if (empty($parts)) {
        // Cannot proceeed without further parts.
        continue;
      }
      if ($purpose === 'account' && !in_array(reset($parts), ['user', 'entity'])) {
        $chained_token_type = $entity_type_id = 'user';
      }
      else {
        $chained_token_type = $entity_type_id = array_shift($parts);
        _context_stack_map_token_type($chained_token_type, $entity_type_id);
      }
      if (!is_string($chained_token_type) || empty($parts)) {
        continue;
      }
      $id = is_string($entity_type_id) ? $entity_type_id : $chained_token_type;
      $context_id = '@' . $service_ids[$type] . ':' . $purpose . '.' . $id;
      /** @var \Drupal\Core\Plugin\Context\ContextRepositoryInterface $context_repository */
      $context_repository = \Drupal::service('context.repository');
      $contexts = $context_repository->getRuntimeContexts([$context_id]);
      $context = !empty($contexts) ? reset($contexts) : NULL;
      if (!$context || !$context->hasContextValue()) {
        // No value from the runtime context retrieved, but at least add
        // cacheability metadata from the according context stack.
        $stack_repo = ContextStackRepository::getService();
        if ($context_stack = $stack_repo->get($purpose)) {
          $bubbleable_metadata->addCacheableDependency($context_stack);
        }
        continue;
      }

      $value = $context->getContextValue();
      if (!is_string($entity_type_id)) {
        // Last chance to get the correct token type.
        if ($value instanceof EntityInterface) {
          $chained_token_type = $entity_type_id = $value->getEntityTypeId();
        }
        else {
          $type_parts = explode(':', $context->getContextData()->getDataDefinition()->getDataType());
          $chained_token_type = end($type_parts);
        }
        _context_stack_map_token_type($chained_token_type, $entity_type_id);
      }
      $bubbleable_metadata->addCacheableDependency($context);
      $chained_token = implode(':', $parts);
      $replacements += \Drupal::token()->generate($chained_token_type, [$chained_token => $original], [$chained_token_type => $value], $options, $bubbleable_metadata);
    }
  }

  return $replacements;
}

/**
 * Maps the given argument to its "real" token type and entity type ID.
 *
 * @param string &$token_type
 *   The claimed token type that will be mapped to its "real" type.
 * @param string|null &$entity_type_id
 *   The variable that holds the value for the entity type ID.
 */
function _context_stack_map_token_type(string &$token_type, ?string &$entity_type_id) {
  $entity_type_manager = \Drupal::entityTypeManager();
  /** @var \Drupal\token\TokenEntityMapperInterface $token_entity_mapper */
  $token_entity_mapper = \Drupal::hasService('token.entity_mapper') ? \Drupal::service('token.entity_mapper') : NULL;
  if ($entity_type_manager->hasDefinition($token_type)) {
    $entity_type_id = $token_type;
    if ($token_entity_mapper) {
      $token_type = $token_entity_mapper->getTokenTypeForEntityType($entity_type_id, TRUE);
    }
    else {
      $entity_type = $entity_type_manager->getDefinition($entity_type_id);
      $token_type = $entity_type->get('token_type') ?: $entity_type_id;
    }
    return;
  }
  elseif ($token_entity_mapper) {
    $entity_type_id = $token_entity_mapper->getEntityTypeForTokenType($token_type, TRUE);
    if (!$entity_type_manager->hasDefinition($entity_type_id)) {
      $entity_type_id = NULL;
    }
    return;
  }
  else {
    if (in_array($token_type, ['term', 'vocabulary'])) {
      $entity_type_id = 'taxonomy_' . $token_type;
      return;
    }
    foreach ($entity_type_manager->getDefinitions() as $entity_type) {
      if ($entity_type->get('token_type') === $token_type) {
        $entity_type_id = $entity_type->id();
        return;
      }
    }
    $entity_type_id = NULL;
  }
}
