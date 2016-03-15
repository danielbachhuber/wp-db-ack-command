wp-db-ack-command
=================================

Search through the database.

[![Build Status](https://travis-ci.org/danielbachhuber/wp-db-ack-command.svg?branch=master)](https://travis-ci.org/danielbachhuber/wp-db-ack-command)

Quick links: [Installing](#installing) | [Using](#using) | [Contributing](#contributing)

## Installing

This package requires the latest nightly version of WP-CLI. Update with `wp cli update --nightly`.

Once you've done so, you can install this package with `wp package install danielbachhuber/wp-db-ack-command`

## Using


~~~
wp db ack <search> [<tables>...]
~~~

Like [ack](http://beyondgrep.com/), but for your WordPress database.
Searches through all or a selection of database tables for a given
string. Outputs colorized references to the string.

Defaults to searching through all tables registered to `$wpdb`. On
multisite, this default is limited to the tables for the current site.

**OPTIONS**

	<search>
		String to search for.

	[<tables>...]
		One or more tables to search through for the string.




## Contributing

Code and ideas are more than welcome.

Please [open an issue](https://github.com/danielbachhuber/wp-db-ack-command/issues) with questions, feedback, and violent dissent. Pull requests are expected to include test coverage.
