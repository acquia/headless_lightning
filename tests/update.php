<?php

/**
 * @file
 * Prepares a database fixture to be updated and run tests.
 */

Drupal::configFactory()
  ->getEditable('core.extension')
  ->clear('module.api_test')
  ->clear('module.openapi_redoc')
  ->save();

Drupal::keyValue('system.schema')->deleteMultiple([
  'api_test',
  'openapi_redoc',
]);
