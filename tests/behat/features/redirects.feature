@api @headless
Feature: Entity form redirections

  Scenario: Creating content as an administrator
    Given I am logged in as a user with the administrator role
    When I click "Content"
    And I click "Add content"
    And I enter "---TESTING---" for "Title"
    And I press "Save"
    Then I should be on "/admin/content"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "Basic page ---TESTING--- has been created."

  Scenario: Editing content as an administrator
    Given I am logged in as a user with the administrator role
    When I click "Content"
    And I click "Add content"
    And I enter "---TESTING---" for "Title"
    And I press "Save"
    And I click "Edit"
    And I press "Save"
    Then I should be on "/admin/content"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "Basic page ---TESTING--- has been updated."

  Scenario: Creating media as an administrator
    Given I am logged in as a user with the administrator role
    When I click "Content"
    And I click "Media"
    And I click "Add media"
    And I click "Video"
    And I enter "---TESTING---" for "Media name"
    And I enter "https://www.youtube.com/watch?v=N2_HkWs7OM0" for "Video URL"
    And I press "Save and publish"
    Then I should be on "/admin/content/media"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "Video ---TESTING--- has been created."

  Scenario: Editing media as an administrator
    Given I am logged in as a user with the administrator role
    When I click "Content"
    And I click "Media"
    And I click "Add media"
    And I click "Video"
    And I enter "---TESTING---" for "Media name"
    And I enter "https://www.youtube.com/watch?v=N2_HkWs7OM0" for "Video URL"
    And I press "Save and publish"
    And I click "Edit"
    And I press "Save and keep published"
    Then I should be on "/admin/content/media"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "Video ---TESTING--- has been updated."

  Scenario: Creating a user account as an administrator
    Given I am logged in as a user with the administrator role
    When I visit "/admin/people"
    And I click "Add user"
    And I enter "---TESTING---" for "Username"
    And I enter "---TESTING---" for "Password"
    And I enter "---TESTING---" for "Confirm password"
    And I press "Create new account"
    Then I should be on "/admin/people"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message containing "Created a new user account for ---TESTING---."

  Scenario: Editing a user account as an administrator
    Given I am logged in as a user with the administrator role
    When I visit "/admin/people"
    And I click "Edit"
    And I press "Save"
    Then I should be on "/admin/people"
    # TODO: Restore when Lightning sets success_message_selector in behat.yml.
    # And I should see the success message "The changes have been saved."
