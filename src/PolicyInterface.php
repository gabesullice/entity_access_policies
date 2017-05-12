<?php

namespace Drupal\entity_access_policies;

use Drupal\Core\Entity\EntityInterface;

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

}
