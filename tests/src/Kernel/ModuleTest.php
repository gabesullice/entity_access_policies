<?php

namespace Drupal\Tests\Kernel;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity_access_policies\LockInterface;
use Drupal\entity_access_policies\PolicyInterface;
use Drupal\KernelTests\KernelTestBase;
use Drupal\node\NodeInterface;
use \Prophecy\Argument;

/**
 * @group entity_access_policies
 */
class ModuleTest extends KernelTestBase {

  public static $modules = ['entity_access_policies'];

  public function setUp() {
    parent::setUp();
    $this->moduleHandler = $this->container->get('module_handler');
  }

  public function testNodeAccessRecords() {
    $policy = $this->prophesize(PolicyInterface::class);
    $policy->applies(Argument::type(EntityInterface::class))->willReturn(TRUE);
    $policy->getLocks(Argument::type(EntityInterface::class))->willReturn([
      $this->getLock([
        'id' => 42,
        'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
        'operations' => ['view'],
      ]),
      $this->getLock([
        'id' => 43,
        'langcode' => LanguageInterface::LANGCODE_DEFAULT,
        'operations' => ['delete'],
      ]),
    ]);

    $policy_manager = $this->prophesize(PluginManagerInterface::class);
    $policy_manager->getDefinitions()->willReturn(['some_policy' => NULL]);
    $policy_manager->createInstance('some_policy')->willReturn($policy->reveal());

    $this->container->set('plugin.manager.entity_access_policy', $policy_manager->reveal());

    $node = $this->prophesize(NodeInterface::class);

    $access_records = $this->moduleHandler->invoke(
      'entity_access_policies',
      'node_access_records',
      [$node->reveal()]
    );

    $this->assertEqual([
      [
        'realm' => 'entity_access_policies:some_policy',
        'gid' => 42,
        'grant_view' => 1,
        'grant_update' => 0,
        'grant_delete' => 0,
        'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
      ],
      [
        'realm' => 'entity_access_policies:some_policy',
        'gid' => 43,
        'grant_view' => 0,
        'grant_update' => 0,
        'grant_delete' => 1,
        'langcode' => LanguageInterface::LANGCODE_DEFAULT,
      ],
    ], $access_records);
  }

  public function testNodeGrants() {
    $account = $this->prophesize(AccountInterface::class);
    $account = $account->reveal();

    $policy = $this->prophesize(PolicyInterface::class);
    $policy->getKeys(Argument::type(AccountInterface::class))->willReturn([
      42,
      43,
    ]);

    $policy_manager = $this->prophesize(PluginManagerInterface::class);
    $policy_manager->getDefinitions()->willReturn(['some_policy' => NULL]);
    $policy_manager->createInstance('some_policy')->willReturn($policy->reveal());

    $this->container->set('plugin.manager.entity_access_policy', $policy_manager->reveal());

    $grants = $this->moduleHandler->invoke(
      'entity_access_policies',
      'node_grants',
      [$account, 'view']
    );

    $this->assertEqual([
      'entity_access_policies:some_policy' => [42, 43],
    ], $grants);
  }

  /**
   * @dataProvider lockToGrantProvider
   */
  public function testLockToGrant($lock_data, $expected) {
    $lock = $this->getLock($lock_data);
    $actual = _entity_access_policies_lock_to_grant('example', $lock);
    $this->assertEqual($expected, $actual);
  }

  protected function getLock($lock_data) {
    $language = $this->prophesize(LanguageInterface::class);
    $language->getId()->willReturn($lock_data['langcode']);

    $lock = $this->prophesize(LockInterface::class);
    $lock->id()->willReturn($lock_data['id']);
    $lock->getLanguage()->willReturn($language->reveal());
    $lock->getOperations()->willReturn($lock_data['operations']);
    return $lock->reveal();
  }

  public function lockToGrantProvider() {
    return [
      [[
        'id' => 888,
        'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
        'operations' => ['view'],
      ], [
        'realm' => 'entity_access_policies:example',
        'gid' => 888,
        'grant_view' => 1,
        'grant_update' => 0,
        'grant_delete' => 0,
        'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
      ]],
      [[
        'id' => 888,
        'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
        'operations' => ['view', 'update'],
      ], [
        'realm' => 'entity_access_policies:example',
        'gid' => 888,
        'grant_view' => 1,
        'grant_update' => 1,
        'grant_delete' => 0,
        'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
      ]],
    ];
  }

}
