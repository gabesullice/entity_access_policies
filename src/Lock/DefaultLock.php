<?php

namespace Drupal\entity_access_policies\Lock;

use Drupal\Core\Entity\EntityInterface;
use Drupal\entity_access_policies\LockInterface;

/**
 * Provides a simple lock for standard use cases.
 */
class DefaultLock implements LockInterface {

  /**
   * The lock ID.
   *
   * @var integer
   */
  protected $id;

  /**
   * The lock operations.
   *
   * @var string[]
   */
  protected $operations;

  /**
   * The applicable lock language.
   *
   * @var Drupal\Core\Language\LanguageInterface
   */
  protected $language;

  /**
   * Creates a new Lock object.
   *
   * @param integer $id
   *   The id of the lock.
   * @param string[] $operations
   *   The allowed entity operations.
   * @param string $language
   *   The language for which this lock applies.
   */
  public function __construct($id, array $operations, $language) {
    $this->id = $id;
    $this->operations = $operations;
    $this->language = $language;
  }

  public static function create($id, array $operations, $language) {
    return new static($id, $operations, $language);
  }

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->id;
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations() {
    return $this->operations;
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguage() {
    return $this->language;
  }

}
