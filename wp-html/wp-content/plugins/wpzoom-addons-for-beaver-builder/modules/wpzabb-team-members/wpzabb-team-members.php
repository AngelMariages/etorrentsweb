<?php

/**
 * @class WPZABBTeamMembersModule
 */
class WPZABBTeamMembersModule extends FLBuilderModule {

	/**
	 * @property $data
	 */
	public $data = null;

	/**
	 * @property $_editor
	 * @protected
	 */
	protected $_editor = null;

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __( 'Team Members', 'wpzabb' ),
			'description'   	=> __( 'Grid with team members.', 'wpzabb' ),
			'category'      	=> WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'team-members/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'team-members/',
            'partial_refresh'	=> true,
		));
	}

	/**
	 * @method update
	 * @param $settings {object}
	 */
	public function update( $settings )
	{
		$member = $settings->members[0];

		// Make sure we have a avatar_src property.
		if(!isset($settings->avatar_src)) {
			$settings->avatar_src = '';
		}

		// Cache the attachment data.
		$data = FLBuilderPhoto::get_attachment_data($member->avatar);

		if($data) {
			$settings->data = $data;
		}

		return $settings;
	}


	/**
	 * @method get_data
	 * @param $member {object}
	 */
	public function get_data( $member )
	{
		if(!$this->data) {

			// Photo source is set to "url".
			if($member->avatar_source == 'url') {
				$this->data = new stdClass();

				$this->data->url = $member->avatar_url;
				$member->avatar_src = $member->avatar_url;
			}

			// Photo source is set to "library".
			else if(is_object($member->avatar)) {
				$this->data = $member->avatar;
			}
			else {
				$this->data = FLBuilderPhoto::get_attachment_data($member->avatar);
			}

			// Data object is empty, use the settings cache.
			if(!$this->data && isset($member->data)) {
				$this->data = $member->data;
			}
		}

		return $this->data;
	}

	/**
	 * @method get_classes
	 * @param $member {object}
	 */
	public function get_classes( $member )
	{
		$classes = array( 'wpzabb-photo-img' );
		
		if ( $member->avatar_source == 'library' ) {
			
			if ( ! empty( $member->avatar ) ) {
				
				$data = self::get_data( $member );
				
				if ( is_object( $data ) ) {
					$classes[] = 'wp-image-' . $data->id;

					if ( isset( $data->sizes ) ) {

						foreach ( $data->sizes as $key => $size ) {
							
							if ( $size->url == $member->avatar_src ) {
								$classes[] = 'size-' . $key;
								break;
							}
						}
					}
				}
			}
			
		}
		
		return implode( ' ', $classes );
	}

	/**
	 * @method get_src
	 * @param $member {object}
	 */
	public function get_src( $member )
	{
		$src = $this->_get_uncropped_url( $member );

		return $src;
	}


	/**
	 * @method get_alt
	 * @param $member {object}
	 */
	public function get_alt( $member )
	{
		$photo = $this->get_data( $member );

		if(!empty($photo->alt)) {
			return htmlspecialchars($photo->alt);
		}
		else if(!empty($photo->description)) {
			return htmlspecialchars($photo->description);
		}
		else if(!empty($photo->caption)) {
			return htmlspecialchars($photo->caption);
		}
		else if(!empty($photo->title)) {
			return htmlspecialchars($photo->title);
		}
	}


	/**
	 * @method _has_source
	 * @param $member {object}
	 * @protected
	 */
	protected function _has_source( $member )
	{
		if($member->avatar_source == 'url' && !empty($member->avatar_url)) {
			return true;
		}
		else if($member->avatar_source == 'library' && !empty($member->avatar_src)) {
			return true;
		}

		return false;
	}

	/**
	 * @method _get_editor
	 * @protected
	 */
	protected function _get_editor()
	{
		foreach ( $settings->members as $i => $member ) {
			if($this->_has_source( $member ) && $this->_editor === null) {

				$url_path  = $this->_get_uncropped_url( $member );
				$file_path = str_ireplace(home_url(), ABSPATH, $url_path);

				if(file_exists($file_path)) {
					$this->_editor = wp_get_image_editor($file_path);
				}
				else {
					$this->_editor = wp_get_image_editor($url_path);
				}
			}
		}

		return $this->_editor;
	}

	/**
	 * @method _get_uncropped_url
	 * @param $member {object}
	 * @protected
	 */
	protected function _get_uncropped_url( $member )
	{
		if($member->avatar_source == 'url') {
			$url = $member->avatar_url;
		}
		else if(!empty($member->avatar_src)) {
			$url = $member->avatar_src;
		}
		else {
			$url = '';
		}

		return $url;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBTeamMembersModule', array(
	'general'      => array( // Tab
		'title'         => __( 'General', 'wpzabb' ), // Tab title
		'sections'      => array( // Tab Sections
			'general'       => array( // Section
				'title'         => '', // Section Title
				'fields'        => array( // Section Fields
					'layout'       => array(
						'type'          => 'select',
						'label'         => __( 'Layout Columns', 'wpzabb' ),
						'default'       => 'layout-3-cols',
						'options'       => array(
							'layout-1-col'		=> __( '1 column', 'wpzabb' ),
							'layout-2-cols'     => __( '2 columns', 'wpzabb' ),
							'layout-3-cols'     => __( '3 columns', 'wpzabb' ),
							'layout-4-cols'     => __( '4 columns', 'wpzabb' ),
						),
					),
					'content_align'     => array(
						'type'          => 'select',
						'label'         => __( 'Content Align', 'wpzabb' ),
						'default'       => 'center',
						'options'       => array(
							'center'	=> __( 'Center', 'wpzabb' ),
							'left'     	=> __( 'Left', 'wpzabb' ),
							'right'     => __( 'Right', 'wpzabb' ),
						),
					),
				),
			),
		),
	),
	'members' 		=> array(
		'title'		=> __( 'Team Members', 'wpzabb' ),
		'sections'	=> array(
			'general'       => array( // Section
				'title'         => '', // Section Title
				'fields'        => array( // Section Fields
					'members'     => array(
						'type'          => 'form',
						'label'         => __( 'Team Member', 'wpzabb' ),
						'form'          => 'member_form', // ID from registered form below
						'preview_text'  => 'name', // Name of a field to use for the preview text
						'multiple'      => true,
					),
				),
			),
		)
	),
	'typography'         => array(
		'title'         => __('Typography', 'wpzabb'),
		'sections'      => array(
			'name_typo'     => array(
				'title'         => __('Name Heading', 'wpzabb'),
				'fields'        => array(
					'tag'           => array(
						'type'          => 'select',
						'label'         => __( 'HTML Tag', 'wpzabb' ),
						'default'       => 'h3',
						'options'       => array(
							'h1'            =>  'h1',
							'h2'            =>  'h2',
							'h3'            =>  'h3',
							'h4'            =>  'h4',
							'h5'            =>  'h5',
							'h6'            =>  'h6'
						)
					),
					'font'          => array(
						'type'          => 'font',
						'default'		=> array(
							'family'		=> 'Default',
							'weight'		=> 300
						),
						'label'         => __('Font', 'wpzabb'),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.wpzabb-team-members-wrap .wpzabb-member-name'
						)
					),
					'new_font_size_unit'     => array(
						'type'          => 'unit',
						'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
						'default'		=> '23',
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-team-members-wrap .wpzabb-member-name',
                            'property'      => 'font-size',
                            'unit'			=> 'px'
                        ),
                        'responsive' => array(
							'placeholder' => array(
								'default' => '23',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
					'line_height_unit'    => array(
						'type'          => 'unit',
						'label'         => __( 'Line Height', 'wpzabb' ),
						'description'   => 'em',
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-team-members-wrap .wpzabb-member-name',
                            'property'      => 'line-height',
                            'unit'			=> 'em'
                        ),
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
					),
					'text_transform' => array(
						'type'          => 'select',
						'label'         => __( 'Text Transform', 'wpzabb' ),
						'default'       => 'none',
						'options'       => array(
							'none'			=> __( 'None', 'wpzabb' ),
							'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
							'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
							'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
						),
						'preview'      => array(
							'type'         => 'css',
							'selector'     => '.wpzabb-team-members-wrap .wpzabb-member-name',
							'property'     => 'text-transform',
						),
					),
					'letter_spacing'     => array(
						'type'          => 'select',
						'label'         => __( 'Letter Spacing', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'custom'        => __( 'Custom', 'wpzabb' ),
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array( 'custom_letter_spacing' ),
							),
						),
					),
		            'custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-team-members-wrap .wpzabb-member-name',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
					'color'    => array( 
						'type'       => 'color',
						'label'         => __('Text Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-name, .fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-name *'
						)
					),
					'name_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '20',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-name',
							'unit'		=> 'px',
						)
					),
					'name_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '10',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-name',
							'unit'		=> 'px',
						)
					),
				)
			),
			'position_typo'    =>  array(
		        'title'		=> __('Position', 'wpzabb'),
		        'fields'    => array(
		            'position_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.wpzabb-team-members-wrap .wpzabb-member-position'
						)
		            ),
		            'position_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
						'default' 		=> '14',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'font-size',
							'selector'  => '.wpzabb-team-members-wrap .wpzabb-member-position',
							'unit'		=> 'px',
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '14',
								'medium' => '',
								'responsive' => '',
							),
						),
		            ),
		            'position_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'line-height',
							'selector'  => '.wpzabb-team-members-wrap .wpzabb-member-position',
							'unit'		=> 'em',
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		            ),
					'position_text_transform' => array(
						'type'          => 'select',
						'label'         => __( 'Text Transform', 'wpzabb' ),
						'default'       => 'none',
						'options'       => array(
							'none'			=> __( 'None', 'wpzabb' ),
							'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
							'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
							'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
						),
						'preview'      => array(
							'type'         => 'css',
							'selector'     => '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-position',
							'property'     => 'text-transform',
						),
					),
					'position_letter_spacing'     => array(
						'type'          => 'select',
						'label'         => __( 'Letter Spacing', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'custom'        => __( 'Custom', 'wpzabb' ),
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array( 'position_custom_letter_spacing' ),
							),
						),
					),
		            'position_custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-position',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
		            'position_color'        => array( 
						'type'       => 'color',
						'label'      => __('Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-position'
						)
					),
					'position_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.wpzabb-team-members-wrap .wpzabb-member-position',
							'unit'	=> 'px',
						)
					),
					'position_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '10',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-bottom',
							'selector' => '.wpzabb-team-members-wrap .wpzabb-member-position',
							'unit'		=> 'px',
						)
					),
		        )
		    ),
			'info_typo'    =>  array(
		        'title'		=> __('Member Info', 'wpzabb'),
		        'fields'    => array(
		            'info_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.wpzabb-team-members-wrap .wpzabb-member-info'
						)
		            ),
		            'info_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'font-size',
							'selector'  => '.wpzabb-team-members-wrap .wpzabb-member-info',
							'unit'		=> 'px',
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		            ),
		            'info_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'line-height',
							'selector'  => '.wpzabb-team-members-wrap .wpzabb-member-info',
							'unit'		=> 'em',
						),
						'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		            ),
					'info_text_transform' => array(
						'type'          => 'select',
						'label'         => __( 'Text Transform', 'wpzabb' ),
						'default'       => 'none',
						'options'       => array(
							'none'			=> __( 'None', 'wpzabb' ),
							'uppercase'		=> __( 'Uppercase', 'wpzabb' ),
							'lowercase'		=> __( 'Lowercase', 'wpzabb' ),
							'capitalize'	=> __( 'Capitalize', 'wpzabb' ),
						),
						'preview'      => array(
							'type'         => 'css',
							'selector'     => '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-info',
							'property'     => 'text-transform',
						),
					),
					'info_letter_spacing'     => array(
						'type'          => 'select',
						'label'         => __( 'Letter Spacing', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'custom'        => __( 'Custom', 'wpzabb' ),
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array( 'info_custom_letter_spacing' ),
							),
						),
					),
		            'info_custom_letter_spacing' => array(
		            	'type'          => 'unit',
		            	'label'         => __( 'Custom Letter Spacing', 'wpzabb' ),
		            	'description'   => 'px',
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
								'medium' => '',
								'responsive' => '',
							),
						),
		                'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-info',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
		            'info_color'        => array( 
						'type'       => 'color',
						'label'      => __('Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-team-members-wrap .wpzabb-member-info'
						)
					),
					'info_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.wpzabb-team-members-wrap .wpzabb-member-info, .wpzabb-team-members-wrap .wpzabb-member-info p',
							'unit'	=> 'px',
						)
					),
					'info_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-bottom',
							'selector' => '.wpzabb-team-members-wrap .wpzabb-member-info, .wpzabb-team-members-wrap .wpzabb-member-info p',
							'unit'		=> 'px',
						)
					),
		        )
		    ),
		)
	)
));


/**
 * Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form('member_form', array(
	'title' => __( 'Add Member', 'wpzabb' ),
	'tabs'  => array(
		'general'      => array( // Tab
			'title'         => __( 'General', 'wpzabb' ), // Tab title
			'sections'      => array( // Tab Sections
				'general'       => array( // Section
					'title'         => '', // Section Title
					'fields'        => array( // Section Fields
						'avatar_source'  => array(
							'type'          => 'select',
							'label'         => __('Avatar Source', 'wpzabb'),
							'default'       => 'library',
							'options'       => array(
								'library'       => __('Media Library', 'wpzabb'),
								'url'           => __('URL', 'wpzabb')
							),
							'toggle'        => array(
								'library'       => array(
									'fields'        => array('avatar')
								),
								'url'           => array(
									'fields'        => array('avatar_url' )
								)
							)
						),
						'avatar'         => array(
							'type'          => 'photo',
							'label'         => __('Avatar', 'wpzabb'),
							'show_remove'	=> true,
							'connections'   => array( 'photo' )
						),
						'avatar_url'     => array(
							'type'          => 'text',
							'label'         => __('Avatar URL', 'wpzabb'),
							'placeholder'   => 'http://www.example.com/my-avatar.jpg',
							'connections'	=> array( 'url' )
						),
						'name'        => array(
							'type'            => 'text',
							'label'           => __('Name', 'wpzabb'),
							'default'         => __('John Doe', 'wpzabb'),
							'preview'         => array(
								'type'            => 'text',
								'selector'        => '.wpzabb-team-members-wrap .wpzabb-member-name'
							),
							'connections'		=> array( 'string', 'html' )
						),
						'link'          => array(
							'type'          => 'link',
							'label'         => __('Link', 'wpzabb'),
							'preview'         => array(
								'type'            => 'none'
							),
							'connections'		=> array( 'url' )
						),
						'link_target'   => array(
							'type'          => 'select',
							'label'         => __('Link Target', 'wpzabb'),
							'default'       => '_self',
							'options'       => array(
								'_self'         => __('Same Window', 'wpzabb'),
								'_blank'        => __('New Window', 'wpzabb')
							),
							'preview'         => array(
								'type'            => 'none'
							)
						),
						'position'        => array(
							'type'            => 'text',
							'label'           => __('Position', 'wpzabb'),
							'default'         => __('Developer', 'wpzabb'),
							'preview'         => array(
								'type'            => 'text',
								'selector'        => '.wpzabb-team-members-wrap .wpzabb-member-position'
							),
							'connections'		=> array( 'string', 'html' )
						),
						'member_info'          => array(
							'type'          => 'editor',
							'label'         => __('Short Info', 'wpzabb'),
						),
					),
				),
			),
		),
	),
));
