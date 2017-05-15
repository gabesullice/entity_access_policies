<?php

namespace Drupal\entity_access_policies;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

interface PolicyInterface {

  /**
   * Whether the policy applies to a given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to test for applicability.
   *
   * @return boolean
   *   Whether the policy applies to a given entity.
   */
  public function applies(EntityInterface $entity);

  /**
   * Any locks applicable to the given entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to for which to generate locks.
   *
   * @return \Drupal\entity_access_policies\LockInterface[]
   *   All the derived locks for the given entity.
   */
  public function getLocks(EntityInterface $entity);

  /**
   * The list of keys this user has access to.
   *
   * The keys should correspond to the IDs of the locks returned by this same
   * policy.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account for which to generate a list of keys.
   *
   * @return string[]
   *   The list of Lock IDs for which this account should be granted access.
   */
  public function getKeys(AccountInterface $account);

}
