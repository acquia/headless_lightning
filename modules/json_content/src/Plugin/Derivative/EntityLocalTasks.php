<?php

namespace Drupal\json_content\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines dynamic local tasks.
 */
class EntityLocalTasks extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The route match.
   *
   * @var RouteMatchInterface
   */
  protected $routeMatch;

  public function __construct(RouteMatchInterface $route_match) {
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $raw_parameters = $this->routeMatch->getRawParameters();
    $entity_type = reset($raw_parameters->keys());
    /* @var $entity \Drupal\Core\Entity\Entity */
    $entity = $this->routeMatch->getParameter($entity_type);

    if (!$entity) {
      return;
    }

    $this->derivatives['json_content.task_id'] = $base_plugin_definition;
    $this->derivatives['json_content.task_id']['route_name'] = 'jsonapi.' . $entity_type . '--' . $entity->bundle() . '.individual';
    $this->derivatives['json_content.task_id']['route_parameters'] = [$entity_type => $entity->uuid()];
    $this->derivatives['json_content.task_id']['base_route'] = 'entity.' . $entity_type . '.canonical';
    return $this->derivatives;
  }

}
