<?php

namespace Drupal\headless_ui\Controller;

use Drupal\Core\Url;
use Drupal\node\Controller\NodeController as BaseNodeController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NodeController extends BaseNodeController {

  /**
   * {@inheritdoc}
   */
  public function addPage() {
    $response = parent::addPage();

    if ($response instanceof RedirectResponse) {
      // This is sloppy but it'll do for now.
      $url = $response->getTargetUrl() . '?destination=/admin/content';
      $response->setTargetUrl($url);
    }
    return $response;
  }

}