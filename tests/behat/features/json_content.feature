@api @headless
Feature: When content is shown, it should be shown in JSON, not rendered.

  @3af3400a
  Scenario Outline: Entity edit pages should provide links to the JSON representation.
    Given I am logged in as a user with the "administrator" role
    And media entities:
      | bundle    | name             | embed_code                                                  | status | path   |
      | tweet     | I'm a tweet      | https://twitter.com/50NerdsofGrey/status/757319527151636480 | 1      | /tweet |
    And node entities:
      | type | title | moderation_state | path  |
      | page | Foo  | draft             | /page |
    When I visit "/<path>"
    And I click "Edit"
    Then I should see "View JSON"

    Examples:
      | path  |
      | page  |
      | tweet |

  @43e31c96 @issue-#2795279
  Scenario: Entity edit pages should not show links to Latest Revision when unpublished edits are present.
    Given I am logged in as a user with the "administrator" role
    And media entities:
      | bundle    | name             | embed_code                                                  | status | path   |
      | tweet     | I'm a tweet      | https://twitter.com/50NerdsofGrey/status/757319527151636480 | 1      | /tweet |
    And node entities:
      | type | title | moderation_state | path  |
      | page | Foo  | draft             | /page |
    When I visit "/page"
    And I click "Edit"
    And I select "draft" from "moderation_state[0][state]"
    And I press "Save"
    When I visit "/page"
    And I click "Edit"
    Then I should not see a "Latest version" link
