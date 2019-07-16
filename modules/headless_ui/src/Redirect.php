<?php

namespace Drupal\headless_ui;

use Doctrine\Common\Inflector\Inflector;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Provides helper methods to redirect various forms.
 */
class Redirect {

  /**
   * Sets the appropriate redirect for an entity form.
   *
   * @param array $form
   *   The complete form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   */
  public static function entityForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\Core\Entity\EntityFormInterface $form_object */
    $form_object = $form_state->getFormObject();
    $form_id = $form_object->getBaseFormId() ?: $form_object->getFormId();

    $redirect = [
      static::class,
      Inflector::camelize($form_id),
    ];

    if (is_callable($redirect)) {
      static::applyHandler($form['actions'], $redirect);
    }
  }

  /**
   * Applies a handler to every child of an action set.
   *
   * @param array $actions
   *   The action set element.
   * @param callable $handler
   *   The handler which should be applied to the children.
   * @param string $handler_type
   *   (optional) The handler type. Defaults to '#submit'.
   *
   * @TODO Make this public in \Drupal\lightning\FormHelper.
   */
  protected static function applyHandler(array &$actions, callable $handler, $handler_type = '#submit') {
    foreach (Element::children($actions) as $key) {
      if (isset($actions[$key][$handler_type])) {
        $actions[$key][$handler_type][] = $handler;
      }
    }
  }

  /**
   * Sets the redirection for a node form.
   *
   * @param array $form
   *   The complete form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   */
  public static function nodeForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('view.content.page_1');
  }

  /**
   * Sets the redirection for a media entity form.
   *
   * @param array $form
   *   The complete form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   */
  public static function mediaForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('view.media.media_page_list');
  }

  /**
   * Sets the redirection for a user profile form.
   *
   * @param array $form
   *   The complete form structure.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   */
  public static function userForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('view.user_admin_people.page_1');
  }

}
