<?php

namespace Drupal\Tests\api_test\Functional;

use Drupal\Component\Serialization\Json;
use Drupal\Tests\BrowserTestBase;

/**
 * @group headless
 * @group api_test
 */
class ApiTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $profile = 'lightning_headless';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['api_test'];

  /**
   * Gets data as anon and authenticated user.
   */
  public function testGet() {
    // Get data that is available anonymously.
    $client = \Drupal::httpClient();
    $url = $this->buildUrl('/jsonapi/node/page/api_test-published-page-content');
    $response = $client->get($url);
    $this->assertEquals(200, $response->getStatusCode());
    $body = Json::decode($response->getBody());
    $this->assertEquals('Published Page', $body['data']['attributes']['title']);

    // Get data that requires authentication.
    $client = \Drupal::httpClient();
    $token = $this->getToken();
    $url = $this->buildUrl('/jsonapi/node/page/api_test-unpublished-page-content');
    $options = [
      'headers' => [
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/vnd.api+json'
      ],
    ];
    $response = $client->get($url, $options);
    $body = Json::decode($response->getBody());
    $this->assertEquals('Unpublished Page', $body['data']['attributes']['title']);
  }

  /**
   * Post new content that requires authentication.
   */
  public function testPost() {
    // Get a count of current nodes so we can assert one more has been created.
    $count = count(\Drupal::entityQuery('node')->execute());

    $client = \Drupal::httpClient();
    $token = $this->getToken();
    $url = $this->buildUrl('/jsonapi/node/page');
    $options = [
      'headers' => [
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/vnd.api+json'
      ],
      'json' => [
        'data' => [
          'type' => 'node--page',
          'attributes' => [
            'title' => 'With my own two hands'
          ]
        ]
      ]
    ];
    $client->post($url, $options);
    $this->assertCount(($count + 1), \Drupal::entityQuery('node')->execute());
  }

  /**
   * Cannot get privileged data anonymously.
   */
  public function testNotAllowedAnon() {
    // Cannot get privileged data anonymously.
    $client = \Drupal::httpClient();
    $url = $this->buildUrl('/jsonapi/node/page/api_test-unpublished-page-content');
    $this->setExpectedException('GuzzleHttp\Exception\ClientException', 'Client error: `GET ' . $url . '` resulted in a `403 Forbidden`');
    $client->get($url);
  }

  /**
   * Cannot get privileged data when authenticated if user isn't authorized.
   */
  public function testNotAllowedAuth() {
    // Get data that requires authentication.
    $client = \Drupal::httpClient();
    $token = $this->getToken();
    $url = $this->buildUrl('/jsonapi/user_role/user_role');
    $options = [
      'headers' => [
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/vnd.api+json'
      ],
    ];
    $response = $client->get($url, $options);
    $body = Json::decode($response->getBody());
    $this->assertArrayHasKey('errors', $body['meta']);
    foreach ($body['meta']['errors'] as $error) {
      $this->assertEquals(403, $error['status']);
    }
  }

  /**
   * The user, client, and content should be removed on uninstall.
   */
  public function testCleanup() {
    \Drupal::service('module_installer')->uninstall(['api_test']);
    $this->assertCount(0, \Drupal::entityQuery('user')->condition('uid', 1, '>')->execute());
    $this->assertCount(0, \Drupal::entityQuery('oauth2_client')->execute());
    $this->assertCount(0, \Drupal::entityQuery('node')->execute());
  }

  /**
   * Gets a token from the oauth endpoint.
   *
   * @return string
   *   The OAuth2 password grant access token from the API.
   */
  protected function getToken() {
    $client = \Drupal::httpClient();;
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

    // The response should have an access token.
    $this->assertArrayHasKey('access_token', $body);

    return $body['access_token'];
  }
}
