<?php

$license = get_option( 'modula_pro_license_key' );
$status  = get_option( 'modula_pro_license_status', false );
$alternative_server = get_option( 'modula_pro_alernative_server' );
$messages = array(
	'no-license' => esc_html__( 'Enter your license key', 'modula-pro' ),
	'activate-license' => esc_html__( 'Activate your license key', 'modula-pro' ),
	'all-good' => __( 'Your license is active until <strong>%s</strong>', 'modula-pro' ),
	'lifetime' => __( 'Your license is active <strong>forever</strong>', 'modula-pro' ),
);

?>
<div class="row">
	<?php do_action( 'modula_license_errors' ) ?>
	<form method="post" action="options.php">

		<?php settings_fields('modula_pro_license_key'); ?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php esc_html_e('License Key', 'modula-pro'); ?>
					</th>
					<td>
						<input id="modula_pro_license_key" name="modula_pro_license_key" type="password" class="regular-text" value="<?php echo esc_attr( $license ); ?>" />
						<label class="description modula-license-label" for="modula_pro_license_key">
							<?php
								if ( '' == $license ) {
									echo $messages['no-license'];
								}elseif ( '' != $license && $status === false ) {
									echo $messages['activate-license'];
								}elseif ( '' != $license && $status !== false && isset( $status->license ) && $status->license == 'valid' ) {
									$date_format = get_option( 'date_format' );

									if ( 'lifetime' == $status->expires ) {
										echo $messages['lifetime'];
									}else{
										$license_expire = date( $date_format, strtotime( $status->expires ) );
										echo sprintf( $messages['all-good'], $license_expire );
									}
								}
									
							?>	
						</label>
					</td>
				</tr>
				<?php if( false !== $license ) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php esc_html_e('Activate License', 'modula-pro'); ?>
						</th>
						<td>
							<?php if( $status !== false && isset( $status->license ) && $status->license == 'valid' ) { ?>
								<?php wp_nonce_field( 'modula_pro_license_nonce', 'modula_pro_license_nonce' ); ?>
								<input type="submit" class="button-secondary" name="modula_pro_license_deactivate" value="<?php esc_html_e('Deactivate License', 'modula-pro'); ?>"/>
							<?php } else {
								wp_nonce_field( 'modula_pro_license_nonce', 'modula_pro_license_nonce' ); ?>
								<input type="submit" class="button-secondary" name="modula_pro_license_activate" value="<?php esc_html_e('Activate License', 'modula-pro'); ?>"/>
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php esc_html_e('Verify Using Alternative Server', 'modula-pro'); ?>
					</th>
					<td>
						<div class="modula-toggle">
							<input class="modula-toggle__input" type="checkbox"
								data-setting="modula_pro_alernative_server"
								id="modula_pro_alernative_server"
								name="modula_pro_alernative_server"
								value="1" <?php  checked(1, $alternative_server, true ) ?>>
							<div class="modula-toggle__items">
								<span class="modula-toggle__track"></span>
								<span class="modula-toggle__thumb"></span>
								<svg class="modula-toggle__off" width="6" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 6 6">
									<path d="M3 1.5c.8 0 1.5.7 1.5 1.5S3.8 4.5 3 4.5 1.5 3.8 1.5 3 2.2 1.5 3 1.5M3 0C1.3 0 0 1.3 0 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z"></path>
								</svg>
								<svg class="modula-toggle__on" width="2" height="6" aria-hidden="true" role="img" focusable="false" viewBox="0 0 2 6">
									<path d="M0 0h2v6H0z"></path>
								</svg>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
	</form>
</div>