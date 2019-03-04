<?php

Drupal::configFactory()
  ->getEditable('core.extension')
  ->clear('module.openapi_redoc')
  ->save();

Drupal::keyValue('system.schema')->delete('openapi_redoc');
