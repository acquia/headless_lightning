<?php

namespace Drupal\Tests\headless_ui\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * @group headless
 * @group headless_ui
 */
class UiTest extends BrowserTestBase {

  use MediaTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected $profile = 'headless_lightning';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['media_test_source'];

  /**
   * {@inheritdoc}
   */
  protected static $configSchemaCheckerExclusions = [
    // @todo Remove when requiring Lightning Layout 2.2 or later.
    'core.entity_view_display.block_content.banner.default',
  ];

  public function test() {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalLogin($this->rootUser);

    $media_type = $this->createMediaType('test');

    $node_type = $this->drupalCreateContentType();
    $this->assertSame(DRUPAL_DISABLED, $node_type->getPreviewMode());
    $this->assertFalse($node_type->displaySubmitted());

    $this->drupalGet('/admin/structure/types/add');
    $assert_session->statusCodeEquals(200);
    $assert_session->fieldNotExists('display_submitted');
    $assert_session->fieldNotExists('options[promote]');
    $assert_session->fieldNotExists('options[sticky]');
    $assert_session->fieldNotExists('preview_mode');

    $this->drupalGet('/node/add/page');
    $assert_session->statusCodeEquals(200);
    $assert_session->pageTextNotContains('Promotion options');

    $this->assertNoManageDisplayLink(
      Url::fromRoute('entity.node_type.collection')
    );
    $this->assertNoManageDisplayLink(
      Url::fromRoute('entity.media_type.collection')
    );
    $this->assertNoManageDisplayLink('/admin/config/people/accounts');
    $this->assertNoManageDisplayLinks('node_type');
    $this->assertNoManageDisplayLinks('media_type');

    // Test redirects when creating and editing content.
    $this->drupalGet('/node/add/page');
    $page->fillField('Title', 'Testing');
    $page->pressButton('Save');
    $assert_session->addressEquals('/admin/content');
    $assert_session->pageTextContains('Basic page Testing has been created.');

    $this->drupalGet('/node/1/edit');
    $page->pressButton('Save');
    $assert_session->addressEquals('/admin/content');
    $assert_session->pageTextContains('Basic page Testing has been updated.');

    // Test redirects when creating and editing media.
    $overview_url = Url::fromRoute('entity.media.collection')->toString();
    $this->drupalGet('/media/add/' . $media_type->id());
    $page->fillField('Name', 'Testing');
    $page->fillField('field_media_test[0][value]', $this->randomString());
    $page->pressButton('Save');
    // @todo Use $assert_session->addressEquals($overview_url) when Lightning
    // Media 3.x is no longer supported.
    $this->assertStringStartsWith($overview_url, parse_url($this->getUrl(), PHP_URL_PATH));
    $assert_session->pageTextContains($media_type->label() . ' Testing has been created.');

    $this->drupalGet('/media/1/edit');
    $page->pressButton('Save');
    // @todo Use $assert_session->addressEquals($overview_url) when Lightning
    // Media 3.x is no longer supported.
    $this->assertStringStartsWith($overview_url, parse_url($this->getUrl(), PHP_URL_PATH));
    $assert_session->pageTextContains($media_type->label() . ' Testing has been updated.');

    // Test redirects when creating and editing user accounts.
    $this->drupalGet('/admin/people');
    $page->clickLink('Add user');
    $page->fillField('Username', 'testing');
    $page->fillField('Password', 'testing');
    $page->fillField('Confirm password', 'testing');
    $page->pressButton('Create new account');
    $assert_session->addressEquals('/admin/access/users');
    $assert_session->pageTextContains('Created a new user account for testing.');

    $this->drupalGet('/user/2/edit');
    $page->pressButton('Save');
    $assert_session->addressEquals('/admin/access/users');
    $assert_session->pageTextContains('The changes have been saved.');
  }

  protected function assertNoManageDisplayLinks($entity_type) {
    /** @var \Drupal\Core\Entity\EntityInterface[] $entities */
    $entities = $this->container
      ->get('entity_type.manager')
      ->getStorage($entity_type)
      ->loadMultiple();

    foreach ($entities as $entity) {
      $this->assertNoManageDisplayLink($entity->toUrl('edit-form'));
    }
  }

  protected function assertNoManageDisplayLink($path) {
    $assert = $this->assertSession();

    $this->drupalGet($path);
    $assert->statusCodeEquals(200);
    $assert->linkNotExists('Manage display');
  }

}
