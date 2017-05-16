<?php
/**
 * Manual Payment Gateway
 *
 * @package     Restrict Content Pro
 * @subpackage  Classes/Gateways/Manual
 * @copyright   Copyright (c) 2017, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.1
*/

class RCP_Payment_Gateway_Manual extends RCP_Payment_Gateway {

	/**
	 * Get things going
	 *
	 * @access public
	 * @since  2.1
	 * @return void
	 */
	public function init() {

		$this->supports[]  = 'one-time';
		$this->supports[]  = 'fees';

	}

	/**
	 * Process registration
	 *
	 * @access public
	 * @since  2.1
	 * @return void
	 */
	public function process_signup() {

		/**
		 * @var RCP_Payments $rcp_payments_db
		 */
		global $rcp_payments_db;

		$member = new RCP_Member( $this->user_id );

		$old_level = get_user_meta( $member->ID, '_rcp_old_subscription_id', true );
		if ( ! empty( $old_level ) && $old_level == $this->subscription_id ) {
			$expiration = $member->calculate_expiration();
		} else {
			delete_user_meta( $member->ID, 'rcp_pending_expiration_date' );
			$expiration = $member->calculate_expiration( true );
		}

		$member->renew( false, 'pending', $expiration );

		// Update payment record with transaction ID.
		$rcp_payments_db->update( $this->payment->id, array(
			'payment_type'   => 'manual',
			'transaction_id' => $this->generate_transaction_id()
		) );

		do_action( 'rcp_process_manual_signup', $member, $this->payment->id, $this );

		wp_redirect( $this->return_url ); exit;

	}

}