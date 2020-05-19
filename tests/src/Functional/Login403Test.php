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
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected $profile = 'headless_lightning';

  /**
   * Tests that a 403 redirects to the user login page.
   */
  public function testLogin403() {
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
