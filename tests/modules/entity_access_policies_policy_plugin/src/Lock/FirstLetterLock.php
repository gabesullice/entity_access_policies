<?php

namespace Drupal\entity_access_policies_policy_plugin\Lock;

use Drupal\entity_access_policies\Lock\LockBase;

class FirstLetterLock extends LockBase {

  /**
   * {@inheritdoc}
   */
  public function id() {
    $label = $entity->label();
    $this->firstLetter = substr($label, 0, 1);
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(
    return ['view', 'delete'];
  );

}
