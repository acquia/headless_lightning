<?php

namespace Drupal\Tests\json_content\Functional;

use Drupal\media\Entity\Media;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * @group headless
 * @group json_content
 */
class JsonContentTest extends BrowserTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected $profile = 'headless_lightning';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['media_test_source'];

  protected function setUp() {
    parent::setUp();
    $this->createMediaType('test', ['id' => 'test']);
  }

  public function test() {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    // Anonymous users see login form on homepage.
    $this->drupalGet('<front>');
    $assert_session->statusCodeEquals(200);
    $form = $assert_session->elementExists('css', '#user-login-form');
    $assert_session->fieldExists('Username', $form);
    $assert_session->fieldExists('Password', $form);

    // Authenticated users without the "access content overview" permission are
    // not redirected from the homepage.
    $account = $this->drupalCreateUser();
    $this->drupalLogin($account);
    $this->drupalGet('<front>');
    $assert_session->pageTextContains('This site has no homepage content');
    $assert_session->addressEquals('/');

    // Authenticated users with the "access content overview" permission are
    // redirected to the /admin/content page.
    $account = $this->drupalCreateUser(['access content overview']);
    $this->drupalLogin($account);
    $this->drupalGet('<front>');
    $assert_session->addressEquals('/admin/content');

    // The "Back to site" link does not appear in the toolbar when on an admin
    // page.
    $account = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($account);
    $this->drupalGet('/admin');
    $assert_session->elementExists('css', 'nav#toolbar-bar');
    $assert_session->linkNotExists('Back to site');

    // Ensure that the "latest version" tab is suppressed.
    $node = $this->drupalCreateNode([
      'type' => 'page',
      'moderation_state' => 'published',
    ]);
    $this->drupalGet('/admin/content');
    $assert_session->statusCodeEquals(200);
    $page->clickLink('Edit ' . $node->getTitle());
    $assert_session->statusCodeEquals(200);
    $assert_session->linkExists('View JSON');
    $assert_session->linkNotExists('Latest version');
    $page->selectFieldOption('moderation_state[0][state]', 'Draft');
    $page->pressButton('Save');
    $assert_session->statusCodeEquals(200);
    $page->clickLink('Edit ' . $node->getTitle());
    $assert_session->statusCodeEquals(200);
    $assert_session->linkExists('View JSON');
    $assert_session->linkNotExists('Latest version');

    Media::create([
      'bundle' => 'test',
      'name' => 'Testing',
      'field_media_test' => $this->randomString(),
    ])->save();
    $this->drupalGet('/admin/content/media-table');
    $assert_session->statusCodeEquals(200);
    $page->clickLink('Edit Testing');
    $assert_session->statusCodeEquals(200);
    $assert_session->linkNotExists('View JSON');
    $assert_session->linkExists('Edit');

    $this->config('media.settings')->set('standalone_url', TRUE)->save();
    $this->getSession()->reload();
    $assert_session->statusCodeEquals(200);
    $assert_session->linkExists('View JSON');
    $assert_session->linkExists('Edit');
  }

}
