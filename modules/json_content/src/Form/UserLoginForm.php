<?php

namespace Drupal\json_content\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Form\UserLoginForm as BaseUserLoginForm;

/**
 * Defines a user login form with specialized redirect handling.
 *
 * @internal
 *   This is an internal part of Headless Lightning and may be changed or
 *   removed at any time without warning. External code should not extend or
 *   use this class in any way!
 */
class UserLoginForm extends BaseUserLoginForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $form_state->setRedirect('<front>');
  }

}
