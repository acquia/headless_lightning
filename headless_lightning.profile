<?php

/**
 * @file
 * Contains installation tasks for the headless_lightning profile.
 */

use Drupal\block\BlockInterface;
use Drupal\Core\Entity\Entity\EntityFormDisplay;

/**
 * Implements hook_install_tasks().
 */
function headless_lightning_install_tasks() {
  $tasks = [];

  $tasks['headless_lightning_set_default_themes'] = [];
  $tasks['headless_lightning_set_403_page'] = [];
  $tasks['headless_lightning_set_default_front_page'] = [];
  $tasks['headless_lightning_set_api_settings'] = [];
  $tasks['headless_lightning_prepare_consumer_form_display'] = [];

  return $tasks;
}

/**
 * Sets the default and administration themes.
 */
function headless_lightning_set_default_themes() {
  Drupal::configFactory()
    ->getEditable('system.theme')
    ->set('default', 'seven')
    ->set('admin', 'seven')
    ->save();
}

/**
 * Sets the default 403 page.
 */
function headless_lightning_set_403_page() {
  Drupal::configFactory()
    ->getEditable('system.site')
    ->set('page.403', '/user/login')
    ->save();
}

/**
 * Sets the default front page to our special-sauce controller.
 */
function headless_lightning_set_default_front_page() {
  Drupal::configFactory()
    ->getEditable('system.site')
    ->set('page.front', '/frontpage')
    ->save();
}

/**
 * Sets up the default settings for Lightning API.
 */
function headless_lightning_set_api_settings() {
  // Expose a 'View JSON' operation for all entities.
  Drupal::configFactory()
    ->getEditable('lightning_api.settings')
    ->set('entity_json', TRUE)
    ->save();
}

/**
 * Modifies the default form display for consumer entities.
 */
function headless_lightning_prepare_consumer_form_display() {
  headless_lightning_entity_get_form_display('consumer', 'consumer')
    ->removeComponent('description')
    ->removeComponent('image')
    ->save();
}

/**
 * Implements hook_ENTITY_TYPE_insert().
 */
function headless_lightning_block_insert(BlockInterface $block) {
  if ($block->id() == 'seven_login') {
    $block->delete();
  }
}

/**
 * Returns the entity form display associated with a bundle and form mode.
 *
 * This is an exact copy of the deprecated entity_get_form_display() from Core
 * 8.6.x except for one change: the default value of the $form_mode parameter.
 *
 * @todo Eliminate this in favor of
 *   \Drupal::service('entity_display.repository')->getFormDisplay() in Core
 *   8.8.x once that is the lowest supported version.
 *
 * @param string $entity_type
 *   The entity type.
 * @param string $bundle
 *   The bundle.
 * @param string $form_mode
 *   The form mode.
 *
 * @return \Drupal\Core\Entity\Display\EntityFormDisplayInterface
 *   The entity form display associated with the given form mode.
 *
 * @see \Drupal\Core\Entity\EntityStorageInterface::create()
 * @see \Drupal\Core\Entity\EntityStorageInterface::load()
 */
function headless_lightning_entity_get_form_display($entity_type, $bundle, $form_mode = 'default') {
  // Try loading the entity from configuration.
  $entity_form_display = EntityFormDisplay::load($entity_type . '.' . $bundle . '.' . $form_mode);

  // If not found, create a fresh entity object. We do not preemptively create
  // new entity form display configuration entries for each existing entity type
  // and bundle whenever a new form mode becomes available. Instead,
  // configuration entries are only created when an entity form display is
  // explicitly configured and saved.
  if (!$entity_form_display) {
    $entity_form_display = EntityFormDisplay::create([
      'targetEntityType' => $entity_type,
      'bundle' => $bundle,
      'mode' => $form_mode,
      'status' => TRUE,
    ]);
  }

  return $entity_form_display;
}
