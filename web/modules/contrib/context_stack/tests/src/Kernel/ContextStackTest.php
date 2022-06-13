<?php

namespace Drupal\Tests\context_stack\Kernel;

use Drupal\context_stack\ContextStackFactory;
use Drupal\context_stack\Plugin\Context\GenericEntityContext;
use Drupal\context_stack\Plugin\Context\SwitchAccountContext;
use Drupal\Core\Session\AccountInterface;
use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\user\Entity\User;

/**
 * Tests for Context Stack logic.
 *
 * @group context_stack
 */
class ContextStackTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'field',
    'filter',
    'text',
    'node',
    'context_stack',
    'context_stack_account',
    'context_stack_view',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {
    parent::setUp();
    $this->installSchema('system', ['sequences']);
    $this->installSchema('user', ['users_data']);
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installConfig(static::$modules);
    user_install();
  }

  /**
   * Tests the "view" stack.
   */
  public function testViewStack() {
    // Create the Article content type with a standard body field.
    /** @var \Drupal\node\NodeTypeInterface $node_type */
    $node_type = NodeType::create(['type' => 'article', 'name' => 'Article']);
    $node_type->save();
    node_add_body_field($node_type);

    /** @var \Drupal\node\NodeInterface $node */
    $node1 = Node::create([
      'type' => 'article',
      'tnid' => 0,
      'uid' => 0,
      'title' => '123',
      'body' => [
        [
          'value' => $this->randomMachineName(32),
          'summary' => $this->randomMachineName(16),
          'format' => 'plain_text',
        ],
      ],
    ]);
    $node1->save();

    /** @var \Drupal\node\NodeInterface $node */
    $node2 = Node::create([
      'type' => 'article',
      'tnid' => 0,
      'uid' => 0,
      'title' => '456',
      'body' => [
        [
          'value' => $this->randomMachineName(32),
          'summary' => $this->randomMachineName(16),
          'format' => 'plain_text',
        ],
      ],
    ]);
    $node2->save();

    $stack_factory = ContextStackFactory::get();

    /** @var \Drupal\context_stack\ContextStackRepositoryInterface $stack_repo */
    $stack_repo = $this->container->get('context_stack.repository');
    $context_stack = $stack_repo->get('view');
    $nesting_level = $context_stack->getNestingLevel();

    $this->assertIsObject($context_stack);
    $this->assertTrue($context_stack->hasPurpose());
    $this->assertEquals('view', $context_stack->getPurpose());

    $this->assertNull($context_stack->getContext('node'));

    $collection_holding_node1 = $stack_factory->createCollection()
      ->addContext(GenericEntityContext::fromEntity($node1));
    $context_stack->push($collection_holding_node1);
    $this->assertSame($nesting_level + 1, $context_stack->getNestingLevel());
    $context = $context_stack->getContext('node');
    $this->assertIsObject($context);
    $this->assertSame($node1, $context->getContextValue());
    $this->assertSame($collection_holding_node1, $context_stack->current());

    $collection_holding_node2 = $stack_factory->createCollection()
      ->addContext(GenericEntityContext::fromEntity($node2));
    $context_stack->push($collection_holding_node2);
    $this->assertSame($nesting_level + 2, $context_stack->getNestingLevel());
    $context = $context_stack->getContext('node');
    $this->assertIsObject($context);
    $this->assertSame($node2, $context->getContextValue());
    $this->assertSame($collection_holding_node2, $context_stack->current());

    $popped = $context_stack->pop();
    $this->assertSame($nesting_level + 1, $context_stack->getNestingLevel());
    $this->assertSame($collection_holding_node2, $popped);
    $this->assertSame($collection_holding_node1, $context_stack->current());
    $this->assertSame($context, $popped->getContext('node'));
    $context = $context_stack->getContext('node');
    $this->assertIsObject($context);
    $this->assertSame($node1, $context->getContextValue());

    $popped = $context_stack->pop();
    $this->assertSame($nesting_level, $context_stack->getNestingLevel());
    $this->assertSame($collection_holding_node1, $popped);
    $this->assertSame($context, $popped->getContext('node'));
    $context = $context_stack->getContext('node');
    $this->assertNull($context);

    // Test the render pipeline using a nested render array.
    $etm = \Drupal::entityTypeManager();
    $view_node1 = $etm->getViewBuilder('node')->view($node1);
    $view_node2 = $etm->getViewBuilder('node')->view($node2);
    $context1 = NULL;
    $view_node1['#pre_render'][] = static function (array $element) use (&$context1) {
      /** @var \Drupal\context_stack\ContextStackInterface $context_stack */
      $context_stack = \Drupal::service('context_stack.view');
      $context1 = $context_stack->getContext('node');
      return $element;
    };
    $context2 = NULL;
    $parent_context_1 = NULL;
    $view_node2['#pre_render'][] = static function (array $element) use (&$context2, &$parent_context_1) {
      /** @var \Drupal\context_stack\ContextStackInterface $context_stack */
      $context_stack = \Drupal::service('context_stack.view');
      $context2 = $context_stack->getContext('node');

      $parent_context_id = '@context_stack.current_parent_content:view.node';
      /** @var \Drupal\Core\Plugin\Context\ContextRepositoryInterface $context_repository */
      $context_repository = \Drupal::service('context.repository');
      $contexts = $context_repository->getRuntimeContexts([$parent_context_id]);
      $parent_context_1 = !empty($contexts) ? reset($contexts) : NULL;
      $parent_context_1 = $parent_context_1->getContextValue();
      return $element;
    };
    $context3 = NULL;
    $parent_context_2 = NULL;
    array_unshift($view_node2['#post_render'], static function ($children, $element) use (&$context3, &$parent_context_2) {
      /** @var \Drupal\context_stack\ContextStackInterface $context_stack */
      $context_stack = \Drupal::service('context_stack.view');
      $context3 = $context_stack->getContext('node');

      $parent_context_id = '@context_stack.current_parent_content:view.node';
      /** @var \Drupal\Core\Plugin\Context\ContextRepositoryInterface $context_repository */
      $context_repository = \Drupal::service('context.repository');
      $contexts = $context_repository->getRuntimeContexts([$parent_context_id]);
      $parent_context_2 = !empty($contexts) ? reset($contexts) : NULL;
      $parent_context_2 = $parent_context_2->getContextValue();
      return $children;
    });
    $context4 = NULL;
    array_unshift($view_node1['#post_render'], static function ($children, $element) use (&$context4) {
      /** @var \Drupal\context_stack\ContextStackInterface $context_stack */
      $context_stack = \Drupal::service('context_stack.view');
      $context4 = $context_stack->getContext('node');
      return $children;
    });
    $view_node1['node2'] = $view_node2;
    \Drupal::service('renderer')->renderRoot($view_node1);

    $this->assertNotNull($context1);
    $this->assertNotNull($context2);
    $this->assertNotNull($context3);
    $this->assertNotNull($context4);
    $this->assertNotNull($parent_context_1);
    $this->assertNotNull($parent_context_2);
    /** @var \Drupal\Core\Plugin\Context\ContextInterface $context1 */
    $this->assertSame($node1, $context1->getContextValue());
    /** @var \Drupal\Core\Plugin\Context\ContextInterface $context2 */
    $this->assertSame($node2, $context2->getContextValue());
    /** @var \Drupal\Core\Plugin\Context\ContextInterface $context3 */
    $this->assertSame($node2, $context3->getContextValue());
    /** @var \Drupal\Core\Plugin\Context\ContextInterface $context4 */
    $this->assertSame($node1, $context4->getContextValue());
    $this->assertSame($node1, $parent_context_1);
    $this->assertSame($node1, $parent_context_1);
  }

  /**
   * Tests account switching using the "account" stack.
   */
  public function testAccountStack() {
    // Create an anonymous account.
    $anonymous = $this->prophesize(AccountInterface::class);
    $anonymous->id()->willReturn(0);
    $anonymous = $anonymous->reveal();
    $anonymous = User::load($anonymous->id());

    // Create an authenticated account.
    $authenticated = User::create([
      'name' => $this->randomMachineName(),
    ]);
    $authenticated->save();
    $authenticated = User::load($authenticated->id());

    /** @var \Drupal\Core\Session\AccountProxyInterface $current_user */
    $current_user = $this->container->get('current_user');

    /** @var \Drupal\context_stack\ContextStackRepositoryInterface $stack_repo */
    $stack_repo = $this->container->get('context_stack.repository');
    $context_stack = $stack_repo->get('account');

    $this->assertIsObject($context_stack);
    $this->assertTrue($context_stack->hasPurpose());
    $this->assertEquals('account', $context_stack->getPurpose());

    $context_stack->addContext(SwitchAccountContext::fromEntity($anonymous));
    $this->assertEquals($current_user->id(), $anonymous->id());

    $context_stack->addContext(SwitchAccountContext::fromEntity($authenticated));
    $this->assertEquals($current_user->id(), $authenticated->id());

    $context_stack->pop();
    $this->assertEquals($current_user->id(), $anonymous->id());
  }

}
