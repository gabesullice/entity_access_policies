<?php

namespace Drupal\entity_access_policies\Lock;

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
   * {@inheritdoc}
   */
  abstract public function id();

  /**
   * {@inheritdoc}
   */
  abstract public function getOperations();

  /**
   * Returns the languages for which this lock applies.
   *
   * @return string[]
   *   Example: [LanguageInterface::LANGUAGE_NOT_SPECIFIED]
   */
  public function getLanguage() {
    return [$this->entity->language()];
  }

}
