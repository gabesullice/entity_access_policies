<?php

namespace Drupal\entity_access_policies;

interface LockInterface {

  /**
   * Returns a unique id for the given lock.
   *
   * @return string
   */
  public function id();

  /**
   * Returns the operations that will be unlocked by a corresponding key.
   *
   * @return string[]
   *   Example: ['view', 'edit']
   */
  public function getOperations();

  /**
   * Returns the languages for which this lock applies.
   *
   * @return string[]
   *   Example: [LanguageInterface::LANGUAGE_NOT_SPECIFIED]
   */
  public function getLanguage();

}
