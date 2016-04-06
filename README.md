runcommand/db-ack
=================

Search through the database.

[![Build Status](https://travis-ci.org/runcommand/db-ack.svg?branch=master)](https://travis-ci.org/runcommand/db-ack)

Quick links: [Using](#using) | [Installing](#installing) | [Contributing](#contributing)

## Using


~~~
wp db ack <search> [<tables>...] [--network] [--all-tables-with-prefix] [--all-tables]
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

**EXAMPLES**

    # Search through database for the 'wordpress-develop' string
    $ wp db ack wordpress-develop
    wp_options:option_value
    1:http://wordpress-develop.dev
    wp_options:option_value
    2:http://wordpress-develop.dev



## Installing

Installing this package requires WP-CLI v0.23.0 or greater. Update to the latest stable release with `wp cli update`.

Once you've done so, you can install this package with `wp package install runcommand/db-ack`

## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/runcommand/db-ack/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.
