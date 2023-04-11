<?php
/**
 * Class WPEL_Deactivate
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 */
final class WPEL_Deactivate extends FWP_Register_Hook_Base_1x0x0
{

    /**
     * @var string
     */
    protected $hook_type = 'deactivation';

    /**
     * Activate network
     * @return void
     */
    protected function network_procedure()
    {
        // network settings
        delete_site_option( 'wpel-pointers' );
    }

    /**
     * Activate site
     * @return void
     */
    protected function site_procedure()
    {
        // delete options
        delete_option( 'wpel-pointers' );
    }

}
