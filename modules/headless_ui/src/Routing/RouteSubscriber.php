<?php

namespace Drupal\headless_ui\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\headless_ui\Controller\NodeController;
use Symfony\Component\Routing\RouteCollection;

class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $route = $collection->get('node.add_page');
    if ($route) {
      $route->setDefault('_controller', NodeController::class . '::addPage');
    }
  }

}
