<?php

namespace Drupal\Tests\headless_ui\Kernel;

use Drupal\headless_ui\Redirect;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * @group headless
 * @group headless_ui
 *
 * @coversDefaultClass \Drupal\headless_ui\Redirect
 */
class RedirectTest extends KernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'headless_ui',
    'system',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installSchema('system', ['sequences']);
    $this->installEntitySchema('user');
  }

  /**
   * @covers ::userForm
   */
  public function testUserForm() {
    $user = $this->createUser();
    $form = $this->container->get('entity.form_builder')->getForm($user);
    $this->assertContains([Redirect::class, 'userForm'], $form['actions']['submit']['#submit']);
  }

}
