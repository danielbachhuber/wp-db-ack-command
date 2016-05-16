Feature: Ack through the database

  Scenario: Search on a single site install
    Given a WP install

    When I run `wp db ack example.com wp_options`
    Then STDOUT should contain:
      """
      wp_options:option_value
      1:http://example.com
      """

  Scenario: Long result strings are truncated
    Given a WP install
    And I run `wp option update searchtest '11111111searchstring11111111'`

    When I run `wp db ack searchstring --before_context=0 --after_context=0`
    Then STDOUT should contain:
      """
      :searchstring
      """

    When I run `wp db ack searchstring --before_context=3 --after_context=3`
    Then STDOUT should contain:
      """
      :111searchstring111
      """

    When I run `wp db ack searchstring`
    Then STDOUT should contain:
      """
      :11111111searchstring11111111
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
