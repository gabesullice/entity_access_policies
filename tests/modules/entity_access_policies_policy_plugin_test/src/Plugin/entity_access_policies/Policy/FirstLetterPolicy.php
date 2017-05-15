<?php

namespace Drupal\entity_access_policies_policy_plugin_test\Plugin\entity_access_policies\Policy;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity_access_policies\Policy\PolicyBase;
use Drupal\entity_access_policies_policy_plugin_test\Lock\FirstLetterLock;

/**
 * @Policy(
 *   id = "FirstLetterPolicy",
 *   label = @Translation("Access Control by Shared First Letter Names"),
 * )
 */
class FirstLetterPolicy extends PolicyBase {

  /**
   * {@inheritdoc}
   */
  public function applies(EntityInterface $entity) {
    return ($entity->getEntityTypeId() == 'node');
  }

  /**
   * {@inheritdoc}
   */
  public function getLocks(EntityInterface $entity) {
    return [FirstLetterLock::create($entity)];
  }

  /**
   * {@inheritdoc}
   */
  public function getKeys(AccountInterface $account) {
    $name = $account->getAccountName();
    return [substr($name, 0, 1)];
  }

}
