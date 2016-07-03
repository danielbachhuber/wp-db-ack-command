runcommand/db-ack
=================

Find a specific string in the database.

[![Build Status](https://travis-ci.org/runcommand/db-ack.svg?branch=master)](https://travis-ci.org/runcommand/db-ack)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using


~~~
wp db ack <search> [<tables>...] [--network] [--all-tables-with-prefix] [--all-tables] [--before_context=<num>] [--after_context=<num>]
~~~

Like [ack](http://beyondgrep.com/), but for your WordPress database.
Searches through all or a selection of database tables for a given
string. Outputs colorized references to the string.

![Example of search for 'wordpress-development'](https://cloud.githubusercontent.com/assets/36432/14318557/4577836a-fbc2-11e5-9b2d-1c84f03a7c02.png)

Defaults to searching through all tables registered to `$wpdb`. On
multisite, this default is limited to the tables for the current site.

**OPTIONS**

	<search>
		String to search for.

	[<tables>...]
		One or more tables to search through for the string.

	[--network]
		Search through all the tables registered to $wpdb in a multisite
		install.

	[--all-tables-with-prefix]
		Search through all tables that match the registered table prefix, even
		if not registered on $wpdb. On one hand, sometimes plugins use tables
		without registering them to $wpdb. On another hand, this could return
		tables you don't expect.

	[--all-tables]
		Search through ALL tables in the database, regardless of the prefix,
		and even if not registered on $wpdb. Overrides --network and
		--all-tables-with-prefix.

	[--before_context=<num>]
		Number of characters to display before the match (for large blobs).
		---
		default: 40
		---

	[--after_context=<num>]
		Number of characters to display after the match (for large blobs).
		---
		default: 40
		---

**EXAMPLES**

    # Search through database for the 'wordpress-develop' string
    $ wp db ack wordpress-develop
    wp_options:option_value
    1:http://wordpress-develop.dev
    wp_options:option_value
    2:http://wordpress-develop.dev



## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install runcommand/db-ack`.

## Contributing

We appreciate you taking the initiative to contribute to this project.

Contributing isn’t limited to just code. We encourage you to contribute in the way that best fits your abilities, by writing tutorials, giving a demo at your local meetup, helping other users with their support questions, or revising our documentation.

### Reporting a bug

Think you’ve found a bug? We’d love for you to help us get it fixed.

Before you create a new issue, you should [search existing issues](https://github.com/runcommand/db-ack/issues?q=label%3Abug%20) to see if there’s an existing resolution to it, or if it’s already been fixed in a newer version.

Once you’ve done a bit of searching and discovered there isn’t an open or fixed issue for your bug, please [create a new issue](https://github.com/runcommand/db-ack/issues/new) with the following:

1. What you were doing (e.g. "When I run `wp post list`").
2. What you saw (e.g. "I see a fatal about a class being undefined.").
3. What you expected to see (e.g. "I expected to see the list of posts.")

Include as much detail as you can, and clear steps to reproduce if possible.

### Creating a pull request

Want to contribute a new feature? Please first [open a new issue](https://github.com/runcommand/db-ack/issues/new) to discuss whether the feature is a good fit for the project.

Once you've decided to commit the time to seeing your pull request through, please follow our guidelines for creating a pull request to make sure it's a pleasant experience:

1. Create a feature branch for each contribution.
2. Submit your pull request early for feedback.
3. Include functional tests with your changes. [Read the WP-CLI documentation](https://wp-cli.org/docs/pull-requests/#functional-tests) for an introduction.
4. Follow the [WordPress Coding Standards](http://make.wordpress.org/core/handbook/coding-standards/).
