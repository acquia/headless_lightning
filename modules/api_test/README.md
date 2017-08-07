# API Test

API Test is a hidden module that provides an OAuth2 Client suitable for testing.

## Client Details

* **UUID**: api_test-oauth2-client
* **Scope**: Basic page creator
* **User**: api-test-user
* **Password**: admin

## Example Usage

You can receive an access token for this User and Client using the following:

```
curl -X POST -d "grant_type=password&client_id=api_test-oauth2-client&client_secret=&username=api-test-user&password=admin" [YOURDOMAIN]/oauth/token
```