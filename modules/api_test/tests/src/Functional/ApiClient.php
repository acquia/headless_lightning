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

  public function test() {
    $assert = $this->assertSession();

    $client = \Drupal::httpClient();

    $options = [
      'json' => [
        'grant_type' => 'password',
        'client_id' => 'api_test-oauth2-client',
        'secret' => '',
        'username' => 'api-test-user',
        'password' => 'admin',
      ],
    ];

    $url = $this->buildUrl('/oauth/token');

    //$request = $client->post($url, $options);
    // @todo process request response.
  }

}
