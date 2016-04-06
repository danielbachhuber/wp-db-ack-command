Feature: Ack through the database

  Scenario: Search on a single site install
    Given a WP install

    When I run `wp db ack example.com wp_options`
    Then STDOUT should contain:
      """
      wp_options:option_value
      1:http://example.com
      """

  Scenario: Search against all tables on a multisite install
    Given a WP multisite install
    And I run `wp site create --slug=foo`

    When I run `wp db ack example.com`
    Then STDOUT should contain:
      """
      wp_options:option_value
      1:http://example.com
      """
    And STDOUT should not contain:
      """
      wp_2_options:option_value
      1:http://example.com/foo
      """

    When I run `wp db ack example.com --network`
    Then STDOUT should contain:
      """
      wp_options:option_value
      1:http://example.com
      """
    And STDOUT should contain:
      """
      wp_2_options:option_value
      1:http://example.com/foo
      """
