<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

class Run_DB_Ack_Command {

	/**
	 * Search through the database.
	 *
	 * Like [ack](http://beyondgrep.com/), but for your WordPress database.
	 * Searches through all or a selection of database tables for a given
	 * string. Outputs colorized references to the string.
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
	 */
	public function __invoke( $args, $assoc_args ) {
		global $wpdb;

		$search = array_shift( $args );

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
