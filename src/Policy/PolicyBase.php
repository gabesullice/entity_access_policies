<?php

namespace Drupal\entity_access_policies\Policy;

use Drupal\entity_access_policies\PolicyInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Component\Plugin\PluginBase;

/**
 * Provides a base class for implementing access policies.
 */
abstract class PolicyBase extends PluginBase implements PolicyInterface {

  /**
   * {@inheritdoc}
   */
  abstract public function applies(EntityInterface $entity);

  /**
   * {@inheritdoc}
   */
  abstract public function getLocks(EntityInterface $entity);

  /**
   * {@inheritdoc}
   */
  abstract public function getKeys(AccountInterface $account);

}
