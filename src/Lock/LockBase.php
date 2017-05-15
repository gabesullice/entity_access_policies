<?php

namespace Drupal\entity_access_policies\Lock;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity_access_policies\LockInterface;

/**
 * Provides a base class for creating custom locks.
 */
abstract class LockBase implements LockInterface {

  /**
   * The entity for which this lock is being generated.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * Creates a new Lock object.
   *
   * @param \Drupal\Core\Entity\EntityInterface;
   *   The entity for which this lock is being generated.
   */
  public function __construct(EntityInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * Creates a new Lock object.
   */
  public static function create(EntityInterface $entity) {
    return new static($entity);
  }

  /**
   * {@inheritdoc}
   */
  abstract public function id();

  /**
   * {@inheritdoc}
   */
  abstract public function getOperations();

  /**
   * {@inheritdoc}
   */
  public function getLanguage() {
    return $this->entity->language();
  }

}
