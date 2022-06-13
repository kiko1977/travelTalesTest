<?php

namespace Drupal\Tests\context_stack\Kernel;

use Drupal\context_stack\Plugin\Context\GenericEntityContext;
use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;

/**
 * Tests for Tokens provided by Context Stack.
 *
 * @group context_stack
 */
class TokenTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'field',
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
   * Tests Tokens using the "view" stack.
   */
  public function testViewTokens() {
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

    /** @var \Drupal\node\NodeInterface $node */
    $node3 = Node::create([
      'type' => 'article',
      'tnid' => 0,
      'uid' => 0,
      'title' => '789',
      'body' => [
        [
          'value' => $this->randomMachineName(32),
          'summary' => $this->randomMachineName(16),
          'format' => 'plain_text',
        ],
      ],
    ]);
    $node3->save();

    /** @var \Drupal\context_stack\ContextStackRepositoryInterface $stack_repo */
    $stack_repo = $this->container->get('context_stack.repository');
    $context_stack = $stack_repo->get('view');

    /** @var \Drupal\Core\Utility\Token $token */
    $token = $this->container->get('token');

    $context_stack->addContext(GenericEntityContext::fromEntity($node1));
    $context_stack->addContext(GenericEntityContext::fromEntity($node2));
    $context_stack->addContext(GenericEntityContext::fromEntity($node3));

    $this->assertEquals('789', $token->replace('[current:view:node:title]'));
    $this->assertEquals('456', $token->replace('[parent:view:node:title]'));
    $this->assertEquals('123', $token->replace('[root:view:node:title]'));

    $context_stack->pop();
    $this->assertEquals('456', $token->replace('[current:view:node:title]'));
    $this->assertEquals('123', $token->replace('[parent:view:node:title]'));
    $this->assertEquals('123', $token->replace('[root:view:node:title]'));

    $context_stack->pop();
    $this->assertEquals('123', $token->replace('[current:view:node:title]'));
    $this->assertEquals('[parent:view:node:title]', $token->replace('[parent:view:node:title]'));
    $this->assertEquals('123', $token->replace('[root:view:node:title]'));

    $context_stack->pop();
    $this->assertEquals('[current:view:node:title]', $token->replace('[current:view:node:title]'));
    $this->assertEquals('[parent:view:node:title]', $token->replace('[parent:view:node:title]'));
    $this->assertEquals('[root:view:node:title]', $token->replace('[root:view:node:title]'));

    $context_stack->addContext(GenericEntityContext::fromEntity($node1), 'entity');
    $context_stack->addContext(GenericEntityContext::fromEntity($node2), 'entity');
    $context_stack->addContext(GenericEntityContext::fromEntity($node3), 'entity');
    $this->assertEquals('789', $token->replace('[current:view:entity:title]'));
    $this->assertEquals('456', $token->replace('[parent:view:entity:title]'));
    $this->assertEquals('123', $token->replace('[root:view:entity:title]'));

    $context_stack->pop();
    $this->assertEquals('456', $token->replace('[current:view:entity:title]'));
    $this->assertEquals('123', $token->replace('[parent:view:entity:title]'));
    $this->assertEquals('123', $token->replace('[root:view:entity:title]'));

    $context_stack->pop();
    $this->assertEquals('123', $token->replace('[current:view:entity:title]'));
    $this->assertEquals('[parent:view:entity:title]', $token->replace('[parent:view:entity:title]'));
    $this->assertEquals('123', $token->replace('[root:view:entity:title]'));

    $context_stack->pop();
    $this->assertEquals('[current:view:entity:title]', $token->replace('[current:view:entity:title]'));
    $this->assertEquals('[parent:view:entity:title]', $token->replace('[parent:view:entity:title]'));
    $this->assertEquals('[root:view:entity:title]', $token->replace('[root:view:entity:title]'));

    $context_stack->addContext(GenericEntityContext::fromEntity($node1), 'article');
    $context_stack->addContext(GenericEntityContext::fromEntity($node2), 'article');
    $context_stack->addContext(GenericEntityContext::fromEntity($node3), 'article');
    $this->assertEquals('789', $token->replace('[current:view:article:title]'));
    $this->assertEquals('456', $token->replace('[parent:view:article:title]'));
    $this->assertEquals('123', $token->replace('[root:view:article:title]'));
    $this->assertEquals('[current:view:node:title]', $token->replace('[current:view:node:title]'));

    $context_stack->pop();
    $this->assertEquals('456', $token->replace('[current:view:article:title]'));
    $this->assertEquals('123', $token->replace('[parent:view:article:title]'));
    $this->assertEquals('123', $token->replace('[root:view:article:title]'));

    $context_stack->pop();
    $this->assertEquals('123', $token->replace('[current:view:article:title]'));
    $this->assertEquals('[parent:view:article:title]', $token->replace('[parent:view:article:title]'));
    $this->assertEquals('123', $token->replace('[root:view:article:title]'));

    $context_stack->pop();
    $this->assertEquals('[current:view:article:title]', $token->replace('[current:view:article:title]'));
    $this->assertEquals('[parent:view:article:title]', $token->replace('[parent:view:article:title]'));
    $this->assertEquals('[root:view:article:title]', $token->replace('[root:view:article:title]'));
  }

}
