<?php

namespace Drupal\Tests\headless_lightning\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests that a 403 redirects to the user login form.
 *
 * @group headless_lightning
 */
class Login403Test extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'headless_lightning';

  /**
   * Tests that a 403 redirects to the user login page.
   */
  public function testLogin403() {
    $config = $this->config('system.theme');
    $this->assertSame('seven', $config->get('admin'));
    $this->assertSame('seven', $config->get('default'));
    $config = $this->config('system.site');
    $this->assertSame('/user/login', $config->get('page.403'));
    $this->assertSame('/frontpage', $config->get('page.front'));
    $this->assertTrue($this->config('lightning_api.settings')->get('entity_json'));

    /** @var \Drupal\Core\Entity\Display\EntityDisplayInterface $display */
    $display = $this->container->get('entity_display.repository')
      ->getFormDisplay('consumer', 'consumer');
    $this->assertNull($display->getComponent('description'));
    $this->assertNull($display->getComponent('image'));

    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('/admin/content');
    $assert_session->statusCodeEquals(403);

    // When the user logs in, they should be forwarded to their original
    // destination.
    $page->fillField('Username', $this->rootUser->getAccountName());
    $page->fillField('Password', $this->rootUser->passRaw);
    $page->pressButton('Log in');
    $assert_session->addressEquals('/admin/content');
    $assert_session->fieldNotExists('Username');
    $assert_session->fieldNotExists('Password');
    $assert_session->statusCodeEquals(200);
  }

}
