<?php

/**
 * Custom modules
 */
if( !class_exists( "WPZOOM_BB_Addon_Pack_Helper" ) ) {
	
	class WPZOOM_BB_Addon_Pack_Helper {

		/*
		* Constructor function that initializes required actions and hooks
		* @Since 1.0
		*/
		function __construct() {

			$this->set_constants();

			add_filter( 'fl_builder_register_settings_form', array( $this, 'set_custom_fields_global_settings' ), 10, 2 );
		}

		function set_constants() {
			$branding         = WPZOOM_BB_Addon_Pack_Helper::get_builder_wpzabb_branding();
			$branding_prefix  = 'wpzabb-';
			$branding_name    = 'WPZABB';
			$branding_modules = __('WPZOOM Modules', 'wpzabb');

			//	Branding - %s
			if (
				is_array( $branding ) &&
				array_key_exists( 'wpzabb-plugin-short-name', $branding ) &&
				$branding['wpzabb-plugin-short-name'] != ''
			) {
				$branding_name = $branding['wpzabb-plugin-short-name'];
			}

			//	Branding - %s Modules
			if ( $branding_name != 'WPZABB') {
				$branding_modules = sprintf( __( '%s Modules', 'wpzabb' ), $branding_name );
			}

			define( 'WPZABB_PREFIX', $branding_prefix );
			define( 'WPZABB_BRANDNAME', $branding_name );
			define( 'WPZABB_CAT', $branding_modules );			
		}
		
		static public function module_cat( $cat = '' ) {
		    return ( class_exists( 'FLBuilderUIContentPanel' ) && ! empty($cat) ) ? $cat : WPZABB_CAT;
		}

		static public function get_builder_wpzabb_branding( $request_key = '' ) {
			$wpzabb = WPZABB_Init::$wpzabb_options['fl_builder_wpzabb_branding'];

			$defaults = array(
				'wpzabb-enable-template-cloud' => 1,
			);


			//	if empty add all defaults
			if( empty( $wpzabb ) ) {
				$wpzabb = $defaults;
			} else {

				//	add new key
				foreach( $defaults as $key => $value ) {
					if( is_array( $wpzabb ) && !array_key_exists( $key, $wpzabb ) ) {
						$wpzabb[$key] = $value;
					} else {
						$wpzabb = wp_parse_args( $wpzabb, $defaults );
					}
				}
			}

			$wpzabb = apply_filters( 'wpzabb_get_builder_wpzabb_branding', $wpzabb );
			
			/**
			 * Return specific requested branding value
			 */
			if( !empty( $request_key ) ) {
				if( is_array($wpzabb) ) {
					$wpzabb = ( array_key_exists( $request_key, $wpzabb ) ) ? $wpzabb[ $request_key ] : '';
				}				
			}

			return $wpzabb;
		}

		static public function get_all_modules() {
			$modules_array = array(
				'spacer-gap'		=> 'Spacer / Gap',
				'separator'         => 'Simple Separator',
				'image-icon'        => 'Image / Icon',
				'image-box'         => 'Image Box',
				'button'            => 'Button',
				'testimonials'      => 'Testimonials',
				'team-members'      => 'Team Members',
				'heading'           => 'Heading',
				'map'            	=> 'Map',
				'posts'            	=> 'Posts',
				'clients'           => 'Clients',
				'food-menu'         => 'Food Menu',
				'woocommerce'       => 'WooCommerce',
				'slideshow'         => 'Slideshow',
				'image-grid'        => 'Image Grid'
			);
			
			return self::prefix_modules( $modules_array );
		}

		static public function prefix_modules( $modules, $prefix = '' ) {
			$all_modules = $modules;
			$prefix 	 = ! empty( $prefix ) ? $prefix : WPZABB_PREFIX;

			// Add dash line after prefix name if not founded
			if ( ! strpos( $prefix, '-' ) ) {
				$prefix .= '-';
			}

			foreach ( $all_modules as $key => $value ) {
				unset($all_modules[ $key ]);
				$new_key = $prefix . $key;
				$all_modules[ $new_key ] = $value;
			}

			return $all_modules;
		}

		/**
		 *	Get builder wpzabb modules
		 *
	 	 *  @since 1.0
		 *	@return array
		 */
		static public function get_builder_wpzabb_modules() {
			$wpzabb_modules 	= WPZABB_Init::$wpzabb_options['fl_builder_wpzabb_modules'];
			$all_modules 		= self::get_all_modules();
			$is_all_modules 	= true;

			//	if empty add all defaults
			if( empty( $wpzabb_modules ) ) {
				$wpzabb_modules 		= self::get_all_modules();
				$wpzabb_modules['all'] 	= 'all';
			} else {
				if ( !isset( $wpzabb_modules['unset_all'] ) ) {
					//	add new key
					foreach( $all_modules as $key => $value ) {
						if( is_array( $wpzabb_modules ) && !array_key_exists( $key, $wpzabb_modules ) ) {
							$wpzabb_modules[ $key ] = $key;
						}
					}
				}
			}

			if ( $is_all_modules == false && isset( $wpzabb_modules['all'] ) ) {
				unset( $wpzabb_modules['all'] );
			}

			return apply_filters( 'wpzabb_get_builder_wpzabb_modules', $wpzabb_modules );
		}

		/**
		 *	Get link rel attribute
		 *
	 	 *  @since 1.0
		 *	@return string
		 */
		static public function get_link_rel( $target, $is_nofollow = 0, $echo = 0 )  {

			$attr = '';
			if( '_blank' == $target ) {
				$attr.= 'noopener';
			}

			if( 1 == $is_nofollow ) {
				$attr.= ' nofollow';
			}

			if( '' == $attr ) {
				return;
			}

			$attr = trim($attr);
			if ( ! $echo  ) {
				return 'rel="'.$attr.'"';
			}
			echo 'rel="'.$attr.'"';
		}


		/**
		 * Set a custom fields settings form.
		 *
		 * @since 1.0
		 * @param array $form The form data.
		 * @param string $id The form id.
		 * @return array
		 */
		static public function set_custom_fields_global_settings( $form, $id ) {

			$form = self::set_custom_form_fields( $form, $id );

			return $form;
		}

		static public function set_custom_form_fields( $form, $type ) {

			$custom_fields = array(
				'general' => array( // tab id
					'responsive' => array( // section id
						'xsmall_breakpoint' => array( // field id
							'type'              => 'text',
							'label'             => __( 'Extra Small Device Breakpoint', 'wpzabb' ),
							'default'           => '480',
							'maxlength'         => '4',
							'size'              => '5',
							'description'       => 'px',
							'sanitize'			=> 'absint',
							'help'              => __( 'The browser width at which the layout will adjust for extra small devices such as phones.', 'wpzabb' ),
						)
					)
				)
			);

			foreach ( $custom_fields as $group => $fields ) {
				if ( isset($form['tabs'][ $group ]) ) {
					$sections = $form['tabs'][ $group ]['sections'];

					foreach ( $sections as $section_id => $section ) {
						if ( isset($fields[ $section_id ]) ) {
							$sections[ $section_id ]['fields'] = array_merge( $sections[ $section_id ]['fields'], $fields[ $section_id ] );
						}
					}

					$form['tabs'][ $group ]['sections'] = $sections;
				}
			}

			return $form;

		}
		
	}
	new WPZOOM_BB_Addon_Pack_Helper();
}
