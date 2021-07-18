<?php

class WPZOOM_Admin_Settings_Sanitization
{
    public function __construct() {
        add_filter('zoom_field_save_title_separator', 'esc_html');
    }
}