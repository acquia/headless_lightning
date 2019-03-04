<?php

use Drupal\block\BlockInterface;

/**
 * Implements hook_install_tasks().
 */
function headless_lightning_install_tasks() {
  $tasks = [];

  $tasks['headless_lightning_set_default_theme'] = [];
  $tasks['headless_lightning_set_403_page'] = [];
  $tasks['headless_lightning_set_default_front_page'] = [];
  $tasks['headless_lightning_set_api_settings'] = [];
  $tasks['headless_lightning_prepare_consumer_form_display'] = [];

  return $tasks;
}

/**
 * Sets the default theme.
 */
function headless_lightning_set_default_theme() {
  Drupal::configFactory()
    ->getEditable('system.theme')
    ->set('default', 'seven')
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
  entity_get_form_display('consumer', 'consumer', 'default')
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
