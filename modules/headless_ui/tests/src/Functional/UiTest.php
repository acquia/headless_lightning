<?php

namespace Drupal\Tests\headless_ui\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * @group headless
 * @group headless_ui
 */
class UiTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'lightning_headless';

  public function test() {
    $assert = $this->assertSession();

    $account = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($account);

    $node_type = $this->drupalCreateContentType();
    $this->assertSame(DRUPAL_DISABLED, $node_type->getPreviewMode());
    $this->assertFalse($node_type->displaySubmitted());

    $this->drupalGet('/admin/structure/types/add');
    $assert->statusCodeEquals(200);
    $assert->fieldNotExists('display_submitted');
    $assert->fieldNotExists('options[promote]');
    $assert->fieldNotExists('options[sticky]');
    $assert->fieldNotExists('preview_mode');

    $this->drupalGet('/admin/structure/types/manage/' . $node_type->id());
    $assert->statusCodeEquals(200);
    $assert->linkNotExists('Manage display');

    $this->drupalGet('/admin/structure');
    $assert->statusCodeEquals(200);
    $assert->linkNotExists('Block layout');
    $assert->linkNotExists('Display modes');
  }

}
