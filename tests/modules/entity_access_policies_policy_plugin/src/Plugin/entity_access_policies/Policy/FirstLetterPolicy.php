<?php

namespace Drupal\entity_access_policies_policy_plugin\Plugin\entity_access_policies\Policy;

class FirstLetterPolicy {

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

}
