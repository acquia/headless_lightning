<?php

namespace Drupal\json_content\Form;

use Drupal\user\Form\UserLoginForm as BaseUserLoginForm;
use Drupal\Core\Form\FormStateInterface;

class UserLoginForm extends BaseUserLoginForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $account = $this->userStorage->load($form_state->get('uid'));
    $form_state->setRedirect('<front>');
    user_login_finalize($account);
  }

}