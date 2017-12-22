<?php

namespace Drupal\json_content;

use Drupal\Core\Menu\LocalTaskDefault;
use Drupal\Core\Routing\RouteMatchInterface;

class EntityJsonTask extends LocalTaskDefault {

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters(RouteMatchInterface $route_match) {
    $parameters = parent::getRouteParameters($route_match);

    $plugin_definition = $this->getPluginDefinition();
    $entity_type = $plugin_definition['entity_type'];
    $parameters[$entity_type] = $route_match->getParameter($entity_type)->uuid();

    return $parameters;
  }

}
