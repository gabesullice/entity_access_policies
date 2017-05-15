<?php

namespace Drupal\entity_access_policies\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Policy annotation object.
 *
 * Plugin Namespace: Plugin\entity_access_policies\Policy
 *
 * @see plugin_api
 *
 * @Annotation
 */
class Policy extends Plugin {

  /**
   * The unique id of the policy.
   *
   * @var string
   */
  public $id;

  /**
   * A descriptive, human-readable label for the policy.
   *
   * @ingroup plugin_translatable
   *
   * @var \Drupal\Core\Annotation\Translation
   */
  public $label;

}
