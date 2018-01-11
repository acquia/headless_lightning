<?php

use Drupal\DrupalExtension\Context\DrupalSubContextBase;

/**
 * Subcontext for interacting with entities that are rendered as JSON.
 */
class JsonEntitySubContext extends DrupalSubContextBase {

  /**
   * The Mink context.
   *
   * @var \Drupal\DrupalExtension\Context\MinkContext
   */
  protected $minkContext;

  /**
   * Pre-scenario hook.
   *
   * @BeforeScenario
   */
  public function gatherContexts() {
    $this->minkContext = $this->getContext('\Drupal\DrupalExtension\Context\MinkContext');
  }

  /**
   * @When I edit the item named :name
   */
  public function iEditTheItemNamed($name) {
    $this->minkContext->clickLink("Edit " . $name);
  }

}
