<?php

/**
 * Class WPEL_Exit_Confirmation_Fields
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 */
final class WPEL_Exit_Confirmation_Fields extends WPEL_Link_Fields_Base
{

  /**
   * Initialize
   */
  protected function init()
  {
    $this->set_settings(array(
      'section_id'        => 'wpel-exit-confirmation-fields',
      'page_id'           => 'wpel-exit-confirmation-fields',
      'option_name'       => 'wpel-exit-confirmation-settings',
      'option_group'      => 'wpel-exit-confirmation-settings',
      'title'             => __('Exit Confirmation', 'wp-external-links'),
      'fields'            => array(
        'exit_preview' => array(
          'label'             => __('Exit Confirmation Preview', 'wp-external-links'),
          'default_value'     => ''

        ),
        'background' => array(
          'label'             => __('Popup Background Color:', 'wp-external-links'),
          'default_value'     => '#FFFFFF'

        ),
        'title' => array(
          'label'             => __('Title:', 'wp-external-links'),
          'default_value'     => __('You are leaving our website', 'wp-external-links'),
        ),
        'title_color' => array(
          'label'             => __('Title Text Color:', 'wp-external-links'),
          'default_value'     => '#FFF'

        ),
        'title_background' => array(
          'label'             => __('Title Background Color:', 'wp-external-links'),
          'default_value'     => '#999'

        ),
        'title_size' => array(
          'label'             => __('Title Size:', 'wp-external-links'),
          'default_value'     => '18'
        ),
        'text' => array(
          'label'             => __('Text:', 'wp-external-links'),
          'default_value'     => 'This link leads outside our website and we are not responsible for its content. If you still want to visit the link, click here:'
        ),
        'text_color' => array(
          'label'             => __('Text Color:', 'wp-external-links'),
          'default_value'     => '#444'
        ),
        'text_size' => array(
          'label'             => __('Text Size:', 'wp-external-links'),
          'default_value'     => '14'
        ),
        'popup_width' => array(
          'label'             => __('Popup Width:', 'wp-external-links'),
          'default_value'     => '400'
        ),
        'popup_height' => array(
          'label'             => __('Popup Height:', 'wp-external-links'),
          'default_value'     => '200'
        ),
        'overlay' => array(
          'label'         => __('Show overlay:', 'wp-external-links'),
          'class'         => 'js-wpel-apply',
          'default_value' => '1',
        ),
        'overlay_color' => array(
          'label'             => __('Overlay Color:', 'wp-external-links'),
          'default_value'     => '#000'
        ),
        'button_text' => array(
          'label'             => __('Button Text:', 'wp-external-links'),
          'default_value'     => 'Stay on the site'
        ),
        'button_size' => array(
          'label'             => __('Button Text Size:', 'wp-external-links'),
          'default_value'     => '14'
        ),
        'button_color' => array(
          'label'             => __('Button Text Color:', 'wp-external-links'),
          'default_value'     => '#FFF'
        ),
        'button_background' => array(
          'label'             => __('Button Background:', 'wp-external-links'),
          'default_value'     => '#1e73be'
        ),
      ),
    ));

    parent::init();
  }

  /**
   * Show field methods
   */
  protected function show_exit_preview(array $args)
  {
    echo '<div id="exit-confirmation-preview"></div>Click <a href="#" class="wpel-exit-confirmation">this link</a> to view a preview of the popup';
  }


  protected function show_title(array $args)
  {
    $this->get_html_fields()->text($args['key'], array(
      'class' => 'regular-text',
    ));

    echo '<p class="description">'
      . __('Title of the Exit Confirmation popup. Leave empty for no title.', 'wp-external-links')
      . '</p>';
  }

  protected function show_text(array $args)
  {
    $this->get_html_fields()->text_area($args['key'], array(
      'class' => 'large-text',
      'rows'  => 4,
      'placeholder' => __('', 'wp-external-links'),
    ));

    echo '<p class="description">' . __('Text of the Exit Confirmation popup. Leave empty for no text.', 'wp-external-links') . '</p>';
  }

  protected function show_text_color(array $args)
  {
    $this->get_html_fields()->color($args['key'], array());

    echo '<p class="description">' . __('Color of the Exit Confirmation popup text.', 'wp-external-links') . '</p>';
  }

  protected function show_title_color(array $args)
  {
    $this->get_html_fields()->color($args['key'], array());

    echo '<p class="description">' . __('Color of the Exit Confirmation popup title.', 'wp-external-links') . '</p>';
  }

  protected function show_title_size(array $args)
  {
    $this->get_html_fields()->number($args['key'], array('class' => 'wpel-field-number', 'unit' => 'px'));

    echo '<p class="description">' . __('Text size of the Exit Confirmation popup title.', 'wp-external-links') . '</p>';
  }

  protected function show_text_size(array $args)
  {
    $this->get_html_fields()->number($args['key'], array('class' => 'wpel-field-number', 'unit' => 'px'));

    echo '<p class="description">' . __('Text size of the Exit Confirmation popup text.', 'wp-external-links') . '</p>';
  }

  protected function show_popup_width(array $args)
  {
    $this->get_html_fields()->number($args['key'], array('class' => 'wpel-field-number', 'unit' => 'px'));

    echo '<p class="description">' . __('Width of the Exit Confirmation popup.', 'wp-external-links') . '</p>';
  }

  protected function show_popup_height(array $args)
  {
    $this->get_html_fields()->number($args['key'], array('class' => 'wpel-field-number', 'unit' => 'px'));

    echo '<p class="description">' . __('Height of the Exit Confirmation popup. Leave empty for auto.', 'wp-external-links') . '</p>';
  }

  protected function show_overlay(array $args)
  {
    $this->get_html_fields()->check_with_label(
      $args['key'],
      __('Show transparent overlay behind popup', 'wp-external-links'),
      '1',
      ''
    );
  }

  protected function show_background(array $args)
  {
    $this->get_html_fields()->color($args['key'], array());

    echo '<p class="description">' . __('Background color of the Exit Confirmation popup.', 'wp-external-links') . '</p>';
  }

  protected function show_title_background(array $args)
  {
    $this->get_html_fields()->color($args['key'], array());

    echo '<p class="description">' . __('Background color of the Exit Confirmation popup title.', 'wp-external-links') . '</p>';
  }


  protected function show_overlay_color(array $args)
  {
    $this->get_html_fields()->color($args['key'], array());
  }

  protected function show_button_color(array $args)
  {
    $this->get_html_fields()->color($args['key'], array());
  }

  protected function show_button_background(array $args)
  {
    $this->get_html_fields()->color($args['key'], array());
  }

  protected function show_button_text(array $args)
  {
    $this->get_html_fields()->text($args['key'], array(
      'class' => 'regular-text',
    ));

    echo '<p class="description">' . __('Text of the button that will cancel leaving website.', 'wp-external-links') . '</p>';
  }

  protected function show_button_size(array $args)
  {
    $this->get_html_fields()->number($args['key'], array('class' => 'wpel-field-number', 'unit' => 'px'));

    echo '<p class="description">' . __('Text size of the Exit Confirmation popup button.', 'wp-external-links') . '</p>';
  }

  /**
   * Validate and sanitize user input before saving to database
   * @param array $new_values
   * @param array $old_values
   * @return array
   */
  protected function before_update(array $new_values, array $old_values)
  {
    $update_values = $new_values;
    $is_valid = true;


    if (false === $is_valid) {
      // error when user input is not valid conform the UI, probably tried to "hack"
      $this->add_error(__('Something went wrong. One or more values were invalid.', 'wp-external-links'));
      return $old_values;
    }

    return $update_values;
  }
}
