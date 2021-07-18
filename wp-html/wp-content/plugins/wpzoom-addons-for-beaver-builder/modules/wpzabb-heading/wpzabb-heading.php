<?php

/**
 * @class WPZABBHeadingModule
 */
class WPZABBHeadingModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('Heading', 'wpzabb'),
			'description'   	=> __('Display a title/page heading.', 'wpzabb'),
			'category'          => WPZOOM_BB_Addon_Pack_Helper::module_cat(),
			'dir'           	=> BB_WPZOOM_ADDON_DIR . 'modules/'. WPZABB_PREFIX .'heading/',
            'url'           	=> BB_WPZOOM_ADDON_URL . 'modules/'. WPZABB_PREFIX .'heading/',
            'partial_refresh'	=> true,
			'icon'				=> 'text.svg',
		));
	}

    /**
	 * @method render_image
	 */
	public function render_image()
	{

		if( $this->settings->separator_style == 'line_image' || $this->settings->separator_style == 'line_icon' ) {
			$imageicon_array = array(

				/* General Section */
				'image_type' => ( $this->settings->separator_style == 'line_image' ) ? 'photo' : ( ( $this->settings->separator_style == 'line_icon' ) ? 'icon' : '' ),

				/* Icon Basics */
				'icon' => $this->settings->icon,
				'icon_size' => $this->settings->icon_size,
				'icon_align' => 'center',

				/* Image Basics */
				'photo_source' => $this->settings->photo_source,
				'photo' => $this->settings->photo,
				'photo_url' => $this->settings->photo_url,
				'img_size' => $this->settings->img_size,
				'img_align' => 'center',
				'photo_src' => ( isset( $this->settings->photo_src ) ) ? $this->settings->photo_src : '' ,
			);

			/* Render HTML Function */
			if( $this->settings->separator_style == 'line_image' ) {
				echo '<div class="wpzabb-image-outter-wrap">';
			}

			FLBuilder::render_module_html( 'wpzabb-image-icon', $imageicon_array );

			if( $this->settings->separator_style == 'line_image' ) {
				echo '</div>';
			}
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('WPZABBHeadingModule', array(
	'general'       => array(
		'title'         => __('General', 'wpzabb'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'heading'        => array(
						'type'            => 'text',
						'label'           => __('Heading', 'wpzabb'),
						'default'         => __('Enter your title here', 'wpzabb'),
						'preview'         => array(
							'type'            => 'text',
							'selector'        => '.wpzabb-heading-text'
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
					)
				)
			),
			'description'	=> array(
				'title'  		=> __( 'Description', 'wpzabb' ),
				'fields' 		=> array(
					'description'	=> array(
						'type'   	=> 'editor',
						'label'  	=> '',
						'rows'   	=> 7,
						'default'	=> '',
						'connections'	=> array( 'string', 'html' ),
					)
				),
			),
			'structure'     => array(
				'title'         => __('Structure', 'wpzabb'),
				'fields'        => array(
					'alignment'     => array(
						'type'          => 'select',
						'label'         => __('Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'left'      =>  __('Left', 'wpzabb'),
							'center'    =>  __('Center', 'wpzabb'),
							'right'     =>  __('Right', 'wpzabb')
						),
						'help'         => __('This is the overall alignment and would apply to all Heading elements', 'wpzabb'),
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-heading-wrap .wpzabb-heading, .wpzabb-heading-wrap .wpzabb-subheading *',
                            'property'      => 'text-align',
                        )
					),
					'r_custom_alignment' => array(
						'type'          => 'select',
						'label'         => __('Responsive Alignment', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'left'      =>  __('Left', 'wpzabb'),
							'center'    =>  __('Center', 'wpzabb'),
							'right'     =>  __('Right', 'wpzabb')
						),
						'preview'         => array(
							'type'            => 'none'
						),
						'help'         => __('The alignment will apply on Mobile', 'wpzabb'),
					),
				)
			),
		)
	),
	'style'         => array(
		'title'         => __('Separator', 'wpzabb'),
		'sections'      => array(
			'separator'          => array(
				'title'         => __('Separator', 'wpzabb'),
				'fields'        => array(
					'separator_style'     => array(
						'type'          => 'select',
						'label'         => __('Separator Style', 'wpzabb'),
						'default'       => 'none',
						'options'       => array(
							'none'      	=>  __('None', 'wpzabb'),
							'line'      	=>  __('Line', 'wpzabb'),
							'line_icon'    	=>  __('Line With Icon', 'wpzabb'),
							'line_image'    =>  __('Line With Image', 'wpzabb'),
							'line_text'     =>  __('Line With Text', 'wpzabb'),
						),
						'toggle'	=> array(
							'line'	=> array(
								'sections'	=> array( 'separator_line' ),
								'fields'	=> array( 'separator_position' )
							),
							'line_icon'	=> array(
								'sections'	=> array( 'separator_line', 'separator_icon_basic' ),
								'fields'	=> array( 'separator_position' )
							),
							'line_image'	=> array(
								'sections'	=> array( 'separator_line', 'separator_img_basic' ),
								'fields'	=> array( 'separator_position' )
							),
							'line_text'	=> array(
								'sections'	=> array( 'separator_line', 'separator_text', 'separator_text_typography' ),
								'fields'	=> array( 'separator_position' )
							),
						)
					),
					'separator_position'     => array(
						'type'          => 'select',
						'label'         => __('Separator Position', 'wpzabb'),
						'default'       => 'center',
						'options'       => array(
							'center'      	=>  __('Between Heading & Description', 'wpzabb'),
							'top'    	=>  __('Top', 'wpzabb'),
							'bottom'    =>  __('Bottom', 'wpzabb'),
						),
					),
				)
			),
			'separator_icon_basic' 	=> 	array(
		        'title'         => __('Icon Basics','wpzabb'),
		        'fields'        => array(
		            'icon'          => array(
		                'type'          => 'icon',
		                'label'         => __('Icon', 'wpzabb'),
		                'show_remove'   => true
		            ),
		            'icon_size'     => array(
		                'type'          => 'text',
		                'label'         => __('Size', 'wpzabb'),
		                'placeholder'   => '30',
		                'maxlength'     => '5',
		                'size'          => '6',
		                'description'   => 'px',
		                'preview'   => array(
                            'type'      => 'css',
                            'selector'  => '.wpzabb-icon-wrap .wpzabb-icon i, .wpzabb-icon-wrap .wpzabb-icon i:before',
                            'property'  => 'font-size',
                            'unit'		=> 'px'
                        ),
		            ),
					'separator_icon_color' => array(
						'type'       => 'color',
						'label'         => __('Icon Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'   => array(
                            'type'      => 'css',
                            'selector'  => '.wpzabb-icon-wrap .wpzabb-icon i, .wpzabb-icon-wrap .wpzabb-icon i:before',
                            'property'  => 'color',
                        ),
					),
		        )
		    ),
			'separator_img_basic' 	=> array(
		        'title'         => __('Image Basics','wpzabb'),
		        'fields'        => array(
		            'photo_source'  => array(
		                'type'          => 'select',
		                'label'         => __('Photo Source', 'wpzabb'),
		                'default'       => 'library',
		                'options'       => array(
		                    'library'       => __('Media Library', 'wpzabb'),
		                    'url'           => __('URL', 'wpzabb')
		                ),
		                'toggle'        => array(
		                    'library'       => array(
		                        'fields'        => array('photo')
		                    ),
		                    'url'           => array(
		                        'fields'        => array('photo_url' )
		                    )
		                )
		            ),
		            'photo'         => array(
		                'type'          => 'photo',
		                'label'         => __('Photo', 'wpzabb'),
		                'show_remove'   => true,
		                'connections'	=> array( 'photo' )
		            ),
		            'photo_url'     => array(
		                'type'          => 'text',
		                'label'         => __('Photo URL', 'wpzabb'),
		                'placeholder'   => 'http://www.example.com/my-photo.jpg',
		            ),
		            'img_size'     => array(
		                'type'          => 'text',
		                'label'         => __('Size', 'wpzabb'),
		                'maxlength'     => '5',
		                'size'          => '6',
		                'description'   => 'px',
						'placeholder' 	=> '50',
						'default'		=> '50'
		            ),
					'responsive_img_size'     => array(
						'type'          => 'text',
						'label'         => __('Responsive Size', 'wpzabb'),
						'maxlength'     => '5',
						'size'          => '6',
						'description'   => 'px',
						'help'			=> __( 'Image size below medium devices. Leave it blank if you want to keep same size', 'wpzabb' ),
						'preview'		=> array(
							'type'	=> 'none'
						)
					),
		        )
		    ),
			'separator_text'			=> array(
				'title'			=> __('Text', 'wpzabb'),
				'fields'		=> array(
					'text_inline'        => array(
						'type'          => 'text',
						'label'         => __('Text', 'wpzabb'),
						'default'       => 'Ultimate',
						'preview'       => array(
							'type'          => 'text',
							'selector'      => '.wpzabb-divider-text',
						)
					),
					'responsive_compatibility' => array(
						'type' => 'select',
						'label' => __('Responsive Compatibility', 'wpzabb'),
						'help' => __('There might be responsive issues for long texts. If you are facing such issues then select appropriate devices width to make your module responsive.', 'wpzabb'),
						'default' => '',
						'options' => array(
							'' => __('None','wpzabb'),
							'wpzabb-responsive-mobile' => __('Small Devices','wpzabb'),
							'wpzabb-responsive-medsmall' => __('Medium & Small Devices','wpzabb'),
						),
					),
				)
			),
			'separator_line'	=> array(
				'title'		=> __('Line Style', 'wpzabb'),
				'fields'	=> array(
					'separator_line_style'		=> array(
						'type'          => 'select',
						'label'         => __('Style', 'wpzabb'),
						'default'       => 'solid',
						'options'       => array(
							'solid'         => __( 'Solid', 'wpzabb' ),
							'dashed'        => __( 'Dashed', 'wpzabb' ),
							'dotted'        => __( 'Dotted', 'wpzabb' ),
							'double'        => __( 'Double', 'wpzabb' )
						),
						'help'          => __('The type of border to use. Double borders must have a height of at least 3px to render properly.', 'wpzabb'),
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-separator, .wpzabb-separator-line > span',
                            'property'      => 'border-top-style',
                        )
					),
					'separator_line_color' => array(
						'type'       => 'color',
						'label'      => __('Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.wpzabb-separator, .wpzabb-separator-line > span',
							'property'      => 'border-top-color'
						)
					),
					'separator_line_height'     => array(
						'type'          => 'text',
						'label'         => __('Thickness', 'wpzabb'),
						'placeholder'   => '1',
						'maxlength'     => '2',
						'size'          => '3',
						'description'   => 'px',
						'help'			=> __( 'Thickness of Border', 'wpzabb' ),
						'preview'       => array(
							'type'          => 'css',
							'selector'      => '.wpzabb-separator, .wpzabb-separator-line > span',
							'property'      => 'border-top-width',
							'unit'			=> 'px',
						)
					),
					'separator_line_width'      => array(
						'type'          => 'text',
						'label'         => __('Width', 'wpzabb'),
						'placeholder'   => '30',
						'maxlength'     => '3',
						'size'          => '5',
						'description'   => '%',
					),
				)
			),
		)
	),
	'typography'         => array(
		'title'         => __('Typography', 'wpzabb'),
		'sections'      => array(
			'heading_typo'     => array(
				'title'         => __('Heading', 'wpzabb'),
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
							'selector'        => '.wpzabb-heading .wpzabb-heading-text'
						)
					),
					'new_font_size_unit'     => array(
						'type'          => 'unit',
						'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
						'preview'         => array(
                            'type'          => 'css',
                            'selector'      => '.wpzabb-heading .wpzabb-heading-text',
                            'property'      => 'font-size',
                            'unit'			=> 'px'
                        ),
                        'responsive' => array(
							'placeholder' => array(
								'default' => '',
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
                            'selector'      => '.wpzabb-heading .wpzabb-heading-text',
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
							'selector'     => '.wpzabb-heading .wpzabb-heading-text',
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
                            'selector'      => '.wpzabb-heading .wpzabb-heading-text',
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
							'selector' => '.fl-module-content.fl-node-content .wpzabb-heading  .wpzabb-heading-text'
						)
					),
					'heading_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.wpzabb-heading',
							'unit'		=> 'px',
						)
					),
					'heading_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'margin-bottom',
							'selector'	=> '.wpzabb-heading',
							'unit'		=> 'px',
						)
					),
				)
			),
			'description_typo'    =>  array(
		        'title'		=> __('Description', 'wpzabb'),
		        'fields'    => array(
		            'desc_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
						'preview'         => array(
							'type'            => 'font',
							'selector'        => '.wpzabb-subheading, .wpzabb-subheading *'
						)
		            ),
		            'desc_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'font-size',
							'selector'  => '.wpzabb-subheading, .wpzabb-subheading *',
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
		            'desc_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'line-height',
							'selector'  => '.wpzabb-subheading, .wpzabb-subheading *',
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
					'desc_text_transform' => array(
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
							'selector'     => '.fl-module-content.fl-node-content .wpzabb-subheading',
							'property'     => 'text-transform',
						),
					),
					'desc_letter_spacing'     => array(
						'type'          => 'select',
						'label'         => __( 'Letter Spacing', 'wpzabb' ),
						'default'       => 'default',
						'options'       => array(
							'default'       => __( 'Default', 'wpzabb' ),
							'custom'        => __( 'Custom', 'wpzabb' ),
						),
						'toggle'        => array(
							'custom'        => array(
								'fields'        => array( 'desc_custom_letter_spacing' ),
							),
						),
					),
		            'desc_custom_letter_spacing' => array(
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
                            'selector'      => '.fl-module-content.fl-node-content .wpzabb-subheading',
                            'property'		=>	'letter-spacing',
                            'unit'			=> 'px'
                        )
		            ),
		            'desc_color'        => array(
						'type'       => 'color',
						'label'      => __('Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property' => 'color',
							'selector' => '.fl-module-content.fl-node-content .wpzabb-subheading, .fl-module-content.fl-node-content .wpzabb-subheading *'
						)
					),
					'desc_margin_top'       => array(
						'type'          => 'text',
						'label'         => __('Margin Top', 'wpzabb'),
						'placeholder'	=> '15',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-top',
							'selector' => '.wpzabb-subheading',
							'unit'	=> 'px',
						)
					),
					'desc_margin_bottom'       => array(
						'type'          => 'text',
						'label'         => __('Margin Bottom', 'wpzabb'),
						'placeholder'	=> '0',
						'size'			=> '5',
						'description'	=> 'px',
						'preview'		=> array(
							'type' => 'css',
							'property' => 'margin-bottom',
							'selector' => '.wpzabb-subheading',
							'unit'		=> 'px',
						)
					),
		        )
		    ),
			'separator_text_typography' => array(
		        'title'     => __('Separator Text Typography', 'wpzabb'),
		        'fields'    => array(
		            'separator_text_tag_selection'   => array(
		                'type'          => 'select',
		                'label'         => __('Text Tag', 'wpzabb'),
		                'default'       => 'h3',
		                'options'       => array(
		                    'h1'      => __('H1', 'wpzabb'),
		                    'h2'      => __('H2', 'wpzabb'),
		                    'h3'      => __('H3', 'wpzabb'),
		                    'h4'      => __('H4', 'wpzabb'),
		                    'h5'      => __('H5', 'wpzabb'),
		                    'h6'      => __('H6', 'wpzabb'),
		                    'div'     => __('Div', 'wpzabb'),
		                    'p'       => __('p', 'wpzabb'),
		                    'span'    => __('span', 'wpzabb'),
		                )
		            ),
		            'separator_text_font_family'       => array(
		                'type'          => 'font',
		                'label'         => __('Font Family', 'wpzabb'),
		                'default'       => array(
		                    'family'        => 'Default',
		                    'weight'        => 'Default'
		                ),
                        'preview'         => array(
                            'type'            => 'font',
                            'selector'        => '.wpzabb-divider-text'
                        )
		            ),
		            'separator_text_font_size_unit'     => array(
		                'type'          => 'unit',
		                'label'         => __( 'Font Size', 'wpzabb' ),
						'description'   => 'px',
	                  	'preview'		=> array(
							'type' => 'css',
							'property'	=> 'font-size',
							'selector'  => '.wpzabb-divider-text',
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
		            'separator_text_line_height_unit'    => array(
		                'type'          => 'unit',
		                'label'         => __( 'Line Height', 'wpzabb' ),
		                'description'   => 'em',
		                'preview'		=> array(
							'type' => 'css',
							'property'	=> 'line-height',
							'selector'  => '.wpzabb-divider-text',
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
		            'separator_text_color' => array(
						'type'       => 'color',
						'label'      => __('Text Color', 'wpzabb'),
						'default'    => '',
						'show_reset' => true,
						'preview'		=> array(
							'type' => 'css',
							'property'	=> 'color',
							'selector'  => '.wpzabb-divider-text',
						)
					),
		        )
		    ),
		)
	)
));
