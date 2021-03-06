<?php

namespace Drupal\entity_access_policies;

use Drupal\KernelTests\KernelTestBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

class PolicyPluginTest extends KernelTestBase {

  /**
   * The policy manager service.
   *
   * @var \Drupal\entity_access_policies\PolicyManagerInterface;
   */
  protected $policyManager;

  public static $modules = [
    'entity_access_policies',
    'example_policy',
  ];

  public function setUp() {
    parent::setUp();
    $this->policyManager = $this->container->get('plugin.manager.entity_access_policy');
  }

  public function testPluginRegistration() {
    $this->assertTrue($this->policyManager->hasDefinition('FirstLetterPolicy'));
  }

  public function testFirstLetterPolicyPlugin_locks() {
    $letter_policy = $this->policyManager->createInstance('FirstLetterPolicy');
    /* @var \Drupal\entity_access_policies\PolicyInterface $letter_policy */

    $node = $this->prophesize(EntityInterface::class);
    $node->getEntityTypeId()->willReturn('node');
    $node->label()->willReturn('Best node title evar!');
    $node = $node->reveal();

    $term = $this->prophesize(EntityInterface::class);
    $term->getEntityTypeId()->willReturn('taxonomy_term');
    $term = $term->reveal();

    $this->assertTrue($letter_policy->applies($node));
    $this->assertFalse($letter_policy->applies($term));

    $locks = $letter_policy->getLocks($node);
    $this->assertEqual(ord('B'), $locks[0]->id());
  }

  public function testFirstLetterPolicyPlugin_keys() {
    $letter_policy = $this->policyManager->createInstance('FirstLetterPolicy');
    /* @var \Drupal\entity_access_policies\PolicyInterface $letter_policy */

    $alice = $this->prophesize(AccountInterface::class);
    $alice->getAccountName()->willReturn('Alice');
    $alice = $alice->reveal();

    $bob = $this->prophesize(AccountInterface::class);
    $bob->getAccountName()->willReturn('Bob');
    $bob = $bob->reveal();

    $this->assertEqual([ord('A')], $letter_policy->getKeys($alice));
    $this->assertEqual([ord('B')], $letter_policy->getKeys($bob));
  }

}
