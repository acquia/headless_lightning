<?php

use Drupal\DrupalExtension\Context\DrupalSubContextBase;
use Drupal\DrupalExtension\Context\MinkContext;

/**
 * Subcontext for interacting with entities that are rendered as JSON.
 */
class JsonEntitySubContext extends DrupalSubContextBase {

  /**
   * @When I edit the item named :name
   */
  public function iEditTheItemNamed($name) {
    $this->getContext(MinkContext::class)->clickLink("Edit $name");
  }

}
