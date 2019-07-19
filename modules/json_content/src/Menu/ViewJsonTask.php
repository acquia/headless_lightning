<?php

namespace Drupal\json_content\Menu;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Menu\LocalTaskDefault;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\jsonapi\ResourceType\ResourceTypeRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines the logic for "View JSON" local tasks.
 *
 * @internal
 *   This is an internal part of Headless Lightning and may be changed or
 *   removed at any time without warning. External code should not extend or
 *   use this class in any way!
 */
final class ViewJsonTask extends LocalTaskDefault implements ContainerFactoryPluginInterface {

  /**
   * The JSON:API resource type repository.
   *
   * @var \Drupal\jsonapi\ResourceType\ResourceTypeRepositoryInterface
   */
  private $resourceTypeRepository;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  private $routeMatch;

  /**
   * ViewJsonTask constructor.
   *
   * @param array $configuration
   *   An array of plugin configuration values.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\jsonapi\ResourceType\ResourceTypeRepositoryInterface $resource_type_repository
   *   The JSON:API resource type repository.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ResourceTypeRepositoryInterface $resource_type_repository, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->resourceTypeRepository = $resource_type_repository;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('jsonapi.resource_type.repository'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return Cache::mergeMaxAges($this->getEntity()->getCacheMaxAge(), parent::getCacheMaxAge());
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags($this->getEntity()->getCacheTags(), parent::getCacheTags());
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts($this->getEntity()->getCacheContexts(), parent::getCacheContexts());
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteName() {
    $entity = $this->getEntity();

    $resource_type = $this->resourceTypeRepository
      ->get($entity->getEntityTypeId(), $entity->bundle())
      ->getTypeName();

    return "jsonapi.$resource_type.individual";
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {
    return [
      'entity' => $this->getEntity()->uuid(),
    ];
  }

  /**
   * Returns the entity being targeted by the local task.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The entity being targeted by the local task, based on the current route.
   */
  private function getEntity() {
    $plugin_definition = $this->getPluginDefinition();
    // The entity_type_id option is set by json_content_local_tasks_alter().
    return $this->routeMatch->getParameter($plugin_definition['entity_type_id']);
  }

}
