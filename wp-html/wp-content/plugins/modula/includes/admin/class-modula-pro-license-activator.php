<?php

class Modula_Pro_License_Activator {

	private $main_item_name = 'Modula Grid Gallery';
	private $verify_alternative_server;
	function __construct() {

		add_action( 'admin_init', array( $this, 'register_license_option' ) );
		add_action( 'admin_init', array( $this, 'activate_license' ) );
		add_action( 'admin_init', array( $this, 'deactivate_license' ) );
		add_action( 'modula_license_errors', array( $this, 'admin_notices' ) );
		add_action( 'update_option_modula_pro_license_key', array( $this, 'after_license_save' ), 10, 2 );

	}

	public function activate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['modula_pro_license_activate'] ) ) {
			
			// run a quick security check
			if ( ! check_admin_referer( 'modula_pro_license_nonce', 'modula_pro_license_nonce' ) ) {
				return;
			}

			// retrieve the license from the database
			$license = trim( get_option( 'modula_pro_license_key' ) );
			
			$this->verify_alternative_server = (isset( $_POST['modula_pro_alernative_server'] ) ) ? $_POST['modula_pro_alernative_server'] : false;
			
			update_option( 'modula_pro_alernative_server', $this->verify_alternative_server );
			
			$store_url = ( '1' == $this->verify_alternative_server ) ? MODULA_PRO_ALTERNATIVE_STORE_URL : MODULA_PRO_STORE_URL;
			
			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => $license,
				'item_id'    => MODULA_PRO_STORE_ITEM_ID,
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$store_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = esc_html__( 'An error occurred, please try again.', 'modula-pro' );
				}
			} else {
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				if ( false === $license_data->success ) {
					switch ( $license_data->error ) {
						case 'expired':
							$message = sprintf(
                                esc_html__( 'Your license key expired on %s.', 'modula-pro' ),
								date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
							);
							break;
						case 'disabled':
						case 'revoked':
							$message = esc_html__( 'Your license key has been disabled.', 'modula-pro' );
							break;
						case 'missing':
							$message = esc_html__( 'Invalid license.', 'modula-pro' );
							break;
						case 'invalid':
						case 'site_inactive':
							$message = esc_html__( 'Your license is not active for this URL.', 'modula-pro' );
							break;
						case 'item_name_mismatch':
							$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'modula-pro' ), $this->main_item_name );
							break;
						case 'no_activations_left':
							$message = esc_html__( 'Your license key has reached its activation limit.', 'modula-pro' );
							break;
						default:
							$message = esc_html__( 'An error occurred, please try again.', 'modula-pro' );
							break;
					}
				}
			}

			// Check if anything passed on a message constituting a failure
			if ( ! empty( $message ) ) {
				$base_url = admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' );
				$redirect = add_query_arg(
					array(
						'sl_activation' => 'false',
						'message'       => urlencode( $message ),
					),
					$base_url
				);
				wp_redirect( $redirect );
				exit();
			}

			// $license_data->license will be either "valid" or "invalid"
			update_option( 'modula_pro_license_status', $license_data );
			wp_redirect( admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' ) );
			exit();
		}
	}

	public function deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['modula_pro_license_deactivate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'modula_pro_license_nonce', 'modula_pro_license_nonce' ) ) {
				return;
			}

			// retrieve the license from the database
			$license = trim( get_option( 'modula_pro_license_key' ) );

			$this->verify_alternative_server = (isset( $_POST['modula_pro_alernative_server'] ) ) ? $_POST['modula_pro_alernative_server'] : false;
			update_option( 'modula_pro_alernative_server', $this->verify_alternative_server );
			$store_url = ( '1' == $this->verify_alternative_server ) ? MODULA_PRO_ALTERNATIVE_STORE_URL : MODULA_PRO_STORE_URL;
			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => $license,
				'item_id'    => MODULA_PRO_STORE_ITEM_ID,
				'url'        => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post(
				$store_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);
			
			// make sure the response came back okay
			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

				if ( is_wp_error( $response ) ) {
					$message = $response->get_error_message();
				} else {
					$message = esc_html__( 'An error occurred, please try again.', 'modula-pro' );
				}

				$base_url = admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' );
				$redirect = add_query_arg(
					array(
						'sl_activation' => 'false',
						'message'       => urlencode( $message ),
					),
					$base_url
				);
				wp_redirect( $redirect );
				exit();
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' ) {
				delete_option( 'modula_pro_license_status' );
			}

			wp_redirect( admin_url( 'edit.php?post_type=modula-gallery&page=modula&modula-tab=licenses' ) );
			exit();
		}
	}

	public function register_license_option() {
		// creates our settings in the options table
		register_setting( 'modula_pro_license_key', 'modula_pro_license_key', array( $this, 'sanitize_license' ) );
	}

	public function sanitize_license( $new ) {
		$old = get_option( 'modula_pro_license_key' );
		if ( $old && $old != $new ) {
			delete_option( 'modula_pro_license_status' ); // new license has been entered, so must reactivate
			delete_transient( 'modula_pro_licensed_extensions' );
		}
		return $new;
	}

	public function admin_notices() {
		if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
			switch ( $_GET['sl_activation'] ) {
				case 'false':
					$message = urldecode( $_GET['message'] );
					?>
					<div class="error">
						<p><?php echo esc_html( $message ); ?></p>
					</div>
					<?php
					break;
				case 'true':
				default:
					// Developers can put a custom success message here for when activation is successful if they way.
					break;
			}
		}
	}

	public function after_license_save( $old_value, $new_value ) {

		$status = get_option( 'modula_pro_license_status' );
		if ( $old_value == $new_value && ! empty( $status ) ) {
			return;
		}

		// retrieve the license from the database
		$license = trim( get_option( 'modula_pro_license_key' ) );

		$this->verify_alternative_server = (isset( $_POST['modula_pro_alernative_server'] ) ) ? $_POST['modula_pro_alernative_server'] : false;
		update_option( 'modula_pro_alernative_server', $this->verify_alternative_server );
		$store_url = ( '1' == $this->verify_alternative_server ) ? MODULA_PRO_ALTERNATIVE_STORE_URL : MODULA_PRO_STORE_URL;
		if ( empty( $license ) ) {
			return;
		}

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => MODULA_PRO_STORE_ITEM_ID,
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_post(
			$store_url,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// make sure the response came back okay
		if ( ! is_wp_error( $response ) && 200 == wp_remote_retrieve_response_code( $response ) ) {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false !== $license_data->success ) {

				// $license_data->license will be either "valid" or "invalid"
				update_option( 'modula_pro_license_status', $license_data );

			}
		}

	}

}

new Modula_Pro_License_Activator();
