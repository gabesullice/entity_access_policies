<?php

namespace Drupal\entity_access_policies\Policy;

use Drupal\entity_access_policies\PolicyInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a base class for implementing access policies.
 */
abstract class PolicyBase implements PolicyInterface {

  /**
   * {@inheritdoc}
   */
  abstract public function applies(EntityInterface $entity);

  /**
   * {@inheritdoc}
   */
  abstract public function getLocks(EntityInterface $entity);

}
