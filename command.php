<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

/**
 * Search through the database.
 */
$db_ack_command = function( $args ) {
	WP_CLI::success( "Hello world" );
};
WP_CLI::add_command( 'db ack', $db_ack_command );
