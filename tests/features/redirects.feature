@api @headless
Feature: Entity form redirections

  @10e85d4d
  Scenario: Creating content as an administrator
    Given I am logged in as an administrator
    When I visit "/node/add/page"
    And I enter "---TESTING---" for "Title"
    And I press "Save"
    Then I should be on "/admin/content"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "Basic page ---TESTING--- has been created."

  @b1e4d949
  Scenario: Editing content as an administrator
    Given I am logged in as an administrator
    When I visit "/node/add/page"
    And I enter "---TESTING---" for "Title"
    And I press "Save"
    And I click "Edit"
    And I press "Save"
    Then I should be on "/admin/content"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "Basic page ---TESTING--- has been updated."

  @fbdd54ba
  Scenario: Creating media as an administrator
    Given I am logged in as an administrator
    When I visit "/media/add/video"
    And I enter "---TESTING---" for "Name"
    And I enter "https://www.youtube.com/watch?v=N2_HkWs7OM0" for "Video URL"
    And I press "Save"
    Then I should be on "/admin/content/media-table"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "Video ---TESTING--- has been created."

  @ec951c88
  Scenario: Editing media as an administrator
    Given I am logged in as an administrator
    When I visit "/media/add/video"
    And I enter "---TESTING---" for "Name"
    And I enter "https://www.youtube.com/watch?v=N2_HkWs7OM0" for "Video URL"
    And I press "Save"
    And I click "Edit"
    And I press "Save"
    Then I should be on "/admin/content/media-table"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "Video ---TESTING--- has been updated."

  @1fc85bb4
  Scenario: Creating a user account as an administrator
    Given I am logged in as an administrator
    When I visit "/admin/people"
    And I click "Add user"
    And I enter "---TESTING---" for "Username"
    And I enter "---TESTING---" for "Password"
    And I enter "---TESTING---" for "Confirm password"
    And I press "Create new account"
    Then I should be on "/admin/access/users"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message containing "Created a new user account for ---TESTING---."

  @4dbd739a
  Scenario: Editing a user account as an administrator
    Given I am logged in as an administrator
    When I visit "/admin/people"
    And I click "Edit"
    And I press "Save"
    Then I should be on "/admin/access/users"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "The changes have been saved."
