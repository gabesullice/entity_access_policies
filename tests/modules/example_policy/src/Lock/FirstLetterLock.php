<?php

namespace Drupal\example_policy\Lock;

use Drupal\entity_access_policies\Lock\LockBase;

class FirstLetterLock extends LockBase {

  /**
   * {@inheritdoc}
   */
  public function id() {
    $label = $this->entity->label();
    return ord($label);
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations() {
    return ['view', 'delete'];
  }

}
