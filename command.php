<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

class Run_DB_Ack_Command {

	/**
	 * Find a specific string in the database.
	 *
	 * Like [ack](http://beyondgrep.com/), but for your WordPress database.
	 * Searches through all or a selection of database tables for a given
	 * string. Outputs colorized references to the string.
	 *
	 * ![Example of search for 'wordpress-development'](https://cloud.githubusercontent.com/assets/36432/14318557/4577836a-fbc2-11e5-9b2d-1c84f03a7c02.png)
	 *
	 * Defaults to searching through all tables registered to `$wpdb`. On
	 * multisite, this default is limited to the tables for the current site.
	 *
	 * ## OPTIONS
	 *
	 * <search>
	 * : String to search for.
	 *
	 * [<tables>...]
	 * : One or more tables to search through for the string.
	 *
	 * [--network]
	 * : Search through all the tables registered to $wpdb in a multisite
	 * install.
	 *
	 * [--all-tables-with-prefix]
	 * : Search through all tables that match the registered table prefix, even
	 * if not registered on $wpdb. On one hand, sometimes plugins use tables
	 * without registering them to $wpdb. On another hand, this could return
	 * tables you don't expect.
	 *
	 * [--all-tables]
	 * : Search through ALL tables in the database, regardless of the prefix,
	 * and even if not registered on $wpdb. Overrides --network and
	 * --all-tables-with-prefix.
	 *
	 * [--before_context=<num>]
	 * : Number of characters to display before the match (for large blobs).
	 * ---
	 * default: 40
	 * ---
	 *
	 * [--after_context=<num>]
	 * : Number of characters to display after the match (for large blobs).
	 * ---
	 * default: 40
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     # Search through database for the 'wordpress-develop' string
	 *     $ wp db ack wordpress-develop
	 *     wp_options:option_value
	 *     1:http://wordpress-develop.dev
	 *     wp_options:option_value
	 *     2:http://wordpress-develop.dev
	 */
	public function __invoke( $args, $assoc_args ) {
		global $wpdb;

		$search = array_shift( $args );
		$before_context = \WP_CLI\Utils\get_flag_value( $assoc_args, 'before_context', 40 );
		$after_context = \WP_CLI\Utils\get_flag_value( $assoc_args, 'after_context', 40 );

		// Avoid constant redefinition in wp-config
		@WP_CLI::get_runner()->load_wordpress();

		$tables = WP_CLI\Utils\wp_get_table_names( $args, $assoc_args );
		foreach( $tables as $table ) {
			list( $primary_keys, $text_columns, $all_columns ) = self::get_columns( $table );
			$primary_key = array_shift( $primary_keys );
			foreach( $text_columns as $column ) {
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT {$primary_key}, {$column} FROM {$table} WHERE {$column} LIKE %s;", '%' . self::esc_like( $search ) . '%' ) );
				foreach( $results as $result ) {
					WP_CLI::log( WP_CLI::colorize( "%G{$table}:{$column}%n" ) );
					$pk_val = WP_CLI::colorize( '%Y' . $result->$primary_key . '%n' );
					$col_val = $result->$column;
					$safe_search = preg_quote( $search, '#' );
					$before_context = '' === $before_context ? $before_context : (int) $before_context;
					$after_context = '' === $after_context ? $after_context : (int) $after_context;
					$search_regex = '#(.{0,' . $before_context . '})(' . $safe_search .')(.{0,' . $after_context . '})#i';
					preg_match_all( $search_regex , $col_val, $matches );
					$bits = array();
					foreach( $matches[0] as $key => $value ) {
						$bits[] = $matches[1][ $key ] . WP_CLI::colorize( '%3%k' . $matches[2][ $key ] . '%n' ) . $matches[3][ $key ];
					}
					$col_val = implode( ' [...] ', $bits );
					WP_CLI::log( "{$pk_val}:{$col_val}" );
				}
			}
		}

	}

	private static function get_columns( $table ) {
		global $wpdb;

		$primary_keys = $text_columns = $all_columns = array();
		foreach ( $wpdb->get_results( "DESCRIBE $table" ) as $col ) {
			if ( 'PRI' === $col->Key ) {
				$primary_keys[] = $col->Field;
			}
			if ( self::is_text_col( $col->Type ) ) {
				$text_columns[] = $col->Field;
			}
			$all_columns[] = $col->Field;
		}
		return array( $primary_keys, $text_columns, $all_columns );
	}

	private static function esc_like( $old ) {
		global $wpdb;

		// Remove notices in 4.0 and support backwards compatibility
		if( method_exists( $wpdb, 'esc_like' ) ) {
			// 4.0
			$old = $wpdb->esc_like( $old );
		} else {
			// 3.9 or less
			$old = like_escape( esc_sql( $old ) );
		}

		return $old;
	}

	private static function is_text_col( $type ) {
		foreach ( array( 'text', 'varchar' ) as $token ) {
			if ( false !== strpos( $type, $token ) )
				return true;
		}

		return false;
	}

}
WP_CLI::add_command( 'db ack', 'Run_DB_Ack_Command' );
