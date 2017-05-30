<?php

namespace Drupal\entity_access_policies;

use Drupal\Core\Plugin\Factory\ContainerFactory;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides a Policy plugin manager.
 *
 * @see \Drupal\entity_access_policies\PolicyInterface
 * @see plugin_api
 */
class PolicyManager extends DefaultPluginManager {

  /**
   * Constructs a PolicyManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/entity_access_policies/Policy',
      $namespaces,
      $module_handler,
      'Drupal\entity_access_policies\PolicyInterface',
      'Drupal\entity_access_policies\Annotation\Policy'
    );
    $this->alterInfo('entity_access_policies_policy_info');
    $this->setCacheBackend($cache_backend, 'entity_access_policies_policy_info_plugins');
    $this->factory = new ContainerFactory($this->getDiscovery());
  }

}
