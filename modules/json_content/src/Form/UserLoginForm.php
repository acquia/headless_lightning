<?php

namespace Drupal\json_content\Form;

use Drupal\user\Form\UserLoginForm as UserLoginFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Overrides the redirect of the login form to
 */
class UserLoginForm extends UserLoginFormBase {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $account = $this->userStorage->load($form_state->get('uid'));
    $form_state->setRedirect('view.content.page_1');
    user_login_finalize($account);
  }

}