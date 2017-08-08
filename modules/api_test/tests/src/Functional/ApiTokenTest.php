<?php

namespace Drupal\Tests\api_test\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Tests\BrowserTestBase;

/**
 * @group headless
 * @group api_test
 */
class ApiTokenTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'lightning_headless';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['api_test'];

  public function test() {
    $client = \Drupal::httpClient();
    $options = [
      'form_params' => [
        'grant_type' => 'password',
        'client_id' => 'api_test-oauth2-client',
        'client_secret' => 'oursecret',
        'username' => 'api-test-user',
        'password' => 'admin',
      ],
    ];
    $url = $this->buildUrl('/oauth/token');

    $response = $client->post($url, $options);
    $body = Json::decode($response->getBody());

    // The response should have an access and refresh token.
    $this->assertArrayHasKey('access_token', $body);
    $this->assertArrayHasKey('refresh_token', $body);

    // The user and client should be removed on uninstall.
    \Drupal::service('module_installer')->uninstall(['api_test']);
    $this->assertCount(0, \Drupal::entityQuery('user')->condition('uid', 1, '>')->execute());
    $this->assertCount(0, \Drupal::entityQuery('oauth2_client')->execute());
  }

}
