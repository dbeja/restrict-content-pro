<?php
/**
 * Admin Actions
 *
 * @package     restrict-content-pro
 * @subpackage  Admin/Admin Actions
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.9
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Process all RCP actions sent via POST and GET
 *
 * @since 2.9
 * @return void
 */
function rcp_process_actions() {
	if ( isset( $_POST['rcp-action'] ) ) {
		do_action( 'rcp_action_' . $_POST['rcp-action'], $_POST );
	}

	if ( isset( $_GET['rcp-action'] ) ) {
		do_action( 'rcp_action_' . $_GET['rcp-action'], $_GET );
	}
}
add_action( 'admin_init', 'rcp_process_actions' );