@api @headless
Feature: Links to content should point to JSON Content, not rendered HTML.

  @3af3400a
  Scenario: Content forms should provide links to the JSON representation
    Given I am logged in as an administrator
    And page content:
      | title          | moderation_state |
      | Page 3af3400a  | draft            |
    When I visit "/admin/content"
    And I click "Edit Page 3af3400a"
    Then I should see "View JSON"

  @11163335
  Scenario: Media forms should provide links to the JSON representation
    Given I am logged in as an administrator
    And media items:
      | bundle    | name           | embed_code                                                  | status |
      | tweet     | Tweet 11163335 | https://twitter.com/50NerdsofGrey/status/757319527151636480 | 1      |
    When I visit "/admin/content/media-table"
    And I click "Edit Tweet 11163335"
    Then I should see "View JSON"

  @43e31c96
  # See http://drupal.org/node/2795279
  Scenario: Edit forms should not show links to the latest version when unpublished edits exist
    Given I am logged in as an administrator
    And page content:
      | title         | moderation_state |
      | Page 43e31c96 | published        |
    When I visit "/admin/content"
    And I click "Edit Page 43e31c96"
    And I select "draft" from "moderation_state[0][state]"
    And I press "Save"
    And I click "Edit Page 43e31c96"
    Then I should not see a "Latest version" link
