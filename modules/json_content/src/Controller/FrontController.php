<?php

namespace Drupal\json_content\Controller;

use Drupal\Core\Controller\ControllerBase;

class FrontController extends ControllerBase {

  /**
   * Displays the login form on the homepage and redirects authenticated users.
   */
  public function frontpage() {
    $build = [];
    if (\Drupal::currentUser()->isAnonymous()) {
      $build['heading'] = [
        '#type' => 'markup',
        '#markup' => $this->t('Please login for access to the content repository.'),
      ];
      $build['form'] = $this->formBuilder()
        ->getForm('Drupal\json_content\Form\UserLoginForm');
    }
    else {
      if (\Drupal::currentUser()->hasPermission('access content overview')) {
        // Permitted users are directed to the admin content page.
        return $this->redirect('view.content.page_1');
      }
      $build['heading'] = [
        '#type' => 'markup',
        '#markup' => $this->t('This site has no homepage content.'),
      ];
    }
    return $build;
  }

}
