<?php

namespace Drupal\example_policy\Plugin\entity_access_policies\Policy;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\entity_access_policies\Policy\PolicyBase;
use Drupal\example_policy\Lock\FirstLetterLock;

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
    return (in_array($entity->getEntityTypeId(), ['node']));
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
    return [ord($name)];
  }

}
