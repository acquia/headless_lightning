<?php

namespace Drupal\json_content\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Psr\Log\InvalidArgumentException;
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
    $this->derivatives = [];

    $entity_type = $this->getEntityType($base_plugin_definition['base_route']);
    /* @var $entity \Drupal\Core\Entity\Entity */
    $entity = $this->routeMatch->getParameter($entity_type);

    if (!$entity) {
      return;
    }

    $this->derivatives['json_content.' . $entity_type . '.view_json'] = $base_plugin_definition;
    $this->derivatives['json_content.' . $entity_type . '.view_json']['route_name'] = 'jsonapi.' . $entity_type . '--' . $entity->bundle() . '.individual';
    $this->derivatives['json_content.' . $entity_type . '.view_json']['route_parameters'] = [$entity_type => $entity->uuid()];
    return parent::getDerivativeDefinitions($base_plugin_definition);
  }

  protected function getEntityType($base_route) {
    if (preg_match('/^entity\.(\S*)\.canonical$/', $base_route, $matches)) {
      return $matches[1];
    }
    throw new InvalidArgumentException('Expected base_route to be in "entity.{TYPE}.cononical" format. Actual: ' . $base_route);
  }

}
