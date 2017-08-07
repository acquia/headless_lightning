<?php

namespace Drupal\Tests\api_test\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * @group headless
 * @group api_test
 */
class ApiClient extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'lightning_headless';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['api_test'];

  public function test() {
    $assert = $this->assertSession();

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

    $request = $client->post($url, $options);
    $body = \GuzzleHttp\json_decode($request->getBody());

    // The response should have an access and refresh token.
    $assert->assert(property_exists($body, 'access_token'), TRUE);
    $assert->assert(property_exists($body, 'refresh_token'), TRUE);
  }

}
