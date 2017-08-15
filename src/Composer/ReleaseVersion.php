<?php

namespace Acquia\headless_lightning\Composer;

use Acquia\Lightning\Composer\ReleaseVersion as BaseReleaseVersion;
use Composer\Script\Event;

/**
 * Updates the version number in Headless Lightning's component info files.
 */
class ReleaseVersion extends BaseReleaseVersion {

  /**
   * {@inheritdoc}
   */
  public static function execute(Event $event) {
    parent::execute($event);
  }

  /**
   * No makefiles in Headless Lightning
   */
  protected static function updateMakeFile($version) {}

}
