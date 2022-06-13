<?php

namespace Drupal\context_stack\Plugin\Context;

use Drupal\context_stack\ContextCollectionInterface;
use Drupal\context_stack\ContextSwitchInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Session\AccountSwitcherInterface;
use Drupal\user\UserInterface;

/**
 * Context for switching the user account when pushed to a context stack.
 */
class SwitchAccountContext extends GenericEntityContext implements ContextSwitchInterface {

  /**
   * The account switcher service.
   *
   * @var \Drupal\Core\Session\AccountSwitcherInterface|null
   */
  protected ?AccountSwitcherInterface $accountSwitcher;

  /**
   * Whether the user has been switched by this context.
   *
   * @var bool
   */
  protected $switched = FALSE;

  /**
   * {@inheritdoc}
   */
  public static function fromEntity(EntityInterface $entity, $label = NULL) {
    if (!($entity instanceof UserInterface)) {
      throw new \LogicException(sprintf("SwitchUserContext expects a User entity, %s given.", get_class($entity)));
    }
    return parent::fromEntity($entity, $label);
  }

  /**
   * {@inheritdoc}
   */
  public static function fromEntityType(EntityTypeInterface $entity_type, $label = NULL) {
    if ($entity_type->id() !== 'user') {
      throw new \LogicException(sprintf("SwitchUserContext expects a User entity type, %s given.", $entity_type->id()));
    }
    return parent::fromEntityType($entity_type, $label);
  }

  /**
   * {@inheritdoc}
   */
  public function pushed(ContextCollectionInterface $collection): void {
    /** @var \Drupal\user\UserInterface $user */
    if (!$this->switched && ($user = $this->getContextValue())) {
      $this->accountSwitcher()->switchTo($user);
      $this->switched = TRUE;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function popped(ContextCollectionInterface $collection): void {
    if ($this->switched) {
      $this->accountSwitcher->switchBack();
      $this->switched = FALSE;
    }
  }

  /**
   * Returns the account switcher service.
   *
   * @return \Drupal\Core\Session\AccountSwitcherInterface
   *   The account switcher service.
   */
  protected function accountSwitcher() {
    if (!isset($this->accountSwitcher)) {
      $this->accountSwitcher = \Drupal::service('account_switcher');
    }
    return $this->accountSwitcher;
  }

  /**
   * {@inheritdoc}
   */
  public function __sleep() {
    return array_filter(parent::__sleep(), function ($value) {
      return !in_array($value, ['switched']);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function __wakeup() {
    parent::__wakeup();
    $this->switched = FALSE;
  }

}
