<?php

namespace Drupal\Tests\headless_lightning\Functional;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;
use Drupal\views\Entity\View;
use Drush\TestTraits\DrushTestTrait;

/**
 * Tests Headless Lightning's database update path.
 *
 * @group headless_lightning
 */
class UpdatePathTest extends UpdatePathTestBase {

  use DrushTestTrait;

  /**
   * {@inheritdoc}
   */
  protected function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      __DIR__ . '/../../fixtures/drupal-8.8.0-update-from-1.2.0-alpha1.php.gz',
    ];
  }

  /**
   * Tests Headless Lightning's database update path.
   */
  public function testUpdatePath() {
    /** @var \Drupal\views\ViewEntityInterface $view */
    $view = View::load('media');
    $display = &$view->getDisplay('default');
    $display['display_options']['fields']['media_bulk_form']['plugin_id'] = 'bulk_form';
    $view->save();

    $this->runUpdates();
    $this->drush('update:lightning', [], ['yes' => NULL]);
  }

}
