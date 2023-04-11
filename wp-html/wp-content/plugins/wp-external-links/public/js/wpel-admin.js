/**
 * WP External Links Plugin
 * Admin
 */

/*global jQuery, window*/
jQuery(function ($) {
  // add custom jQuery show/hide function
  $.extend($.fn, {
    wpelShow: function () {
      var self = this;
      this.stop({ clearQueue: true, jumpToEnd: true });
      this.fadeIn({
        duration: 500,
        queue: false,
        complete: function () {
          self.removeClass('wpel-hidden');
        },
      });
    },
    wpelHide: function () {
      var self = this;
      this.stop({ clearQueue: true, jumpToEnd: true });
      this.fadeOut({
        duration: 500,
        queue: false,
        complete: function () {
          self.addClass('wpel-hidden');
        },
      });
    },
  });

  var $wrapper = $('.wpel-settings-page');

  /**
   * Apply Sections Settings
   */
  $wrapper.on('change', '.js-wpel-apply input', function () {
    var applyAll = $(this).is(':checked');
    var $items = $wrapper.find('.js-wpel-apply-child');

    if (applyAll) {
      $items.wpelHide();
    } else {
      $items.wpelShow();
    }
  });

  // trigger immediatly
  $wrapper.find('.js-wpel-apply input[type="checkbox"]').change();

  /**
   * Link Settings
   */
  $wrapper.on('change', '.js-icon-type select', function () {
    var iconType = $(this).val();
    var $itemsChild = $wrapper.find('.js-icon-type-child');
    var $itemsDepend = $wrapper.find('.js-icon-type-depend');

    $itemsChild.hide();

    if (iconType === 'image') {
      $itemsDepend.wpelShow();
      $itemsChild.filter('.js-icon-type-image').wpelShow();
    } else if (iconType === 'dashicon') {
      $itemsDepend.wpelShow();
      $itemsChild.filter('.js-icon-type-dashicon').wpelShow();
    } else if (iconType === 'fontawesome') {
      $itemsDepend.wpelShow();
      $itemsChild.filter('.js-icon-type-fontawesome').wpelShow();
    } else {
      $itemsDepend.wpelHide();
    }
  });

  $wrapper.on('change', '.js-apply-settings input[type="checkbox"]', function () {
    var $items = $wrapper.find('.form-table tr').not('.js-apply-settings');

    if ($(this).prop('checked')) {
      $items.wpelShow();
      $wrapper.find('.js-icon-type select').change();
    } else {
      $items.wpelHide();
    }
  });

  // trigger immediatly
  $wrapper.find('.js-apply-settings input[type="checkbox"]').change();

  /**
   * Support
   * Copy to clipboard
   */
  $wrapper.on('click', '.js-wpel-copy', function (e) {
    e.preventDefault();

    var node = $wrapper.find('.js-wpel-copy-target').get(0);
    node.select();

    var range = document.createRange();
    range.selectNode(node);
    window.getSelection().addRange(range);

    try {
      document.execCommand('copy');
    } catch (err) {}
  });

  /**
   * Help documentation links/buttons
   */
  $wrapper.on('click', '[data-wpel-help]', function () {
    var helpKey = $(this).data('wpel-help');

    if (helpKey) {
      // activate given tab
      $('#tab-link-' + helpKey + ' a').click();
    } else {
      // activate first tab
      $('.contextual-help-tabs li a').first().click();
    }

    $('#contextual-help-link[aria-expanded="false"]').click();
  });

  // show current tab
  $wrapper.find('form').wpelShow();
  // for network pages
  $('.wpel-network-page').find('form').wpelShow();

  // pro dialog
  $('a.nav-tab-pro').on('click', function (e) {
    e.preventDefault();

    open_upsell('tab');

    return false;
  });

  $('#wpwrap').on('click', '.open-pro-dialog', function (e) {
    e.preventDefault();
    $(this).blur();

    pro_feature = $(this).data('pro-feature');
    if (!pro_feature) {
      pro_feature = $(this).parent('label').attr('for');
    }
    open_upsell(pro_feature);

    return false;
  });

  $('#wpel-pro-dialog').dialog({
    dialogClass: 'wp-dialog wpel-pro-dialog',
    modal: true,
    resizable: false,
    width: 850,
    height: 'auto',
    show: 'fade',
    hide: 'fade',
    close: function (event, ui) {},
    open: function (event, ui) {
      $(this).siblings().find('span.ui-dialog-title').html('WP Links PRO is here!');
      wpel_fix_dialog_close(event, ui);
    },
    autoOpen: false,
    closeOnEscape: true,
  });

  function clean_feature(feature) {
    feature = feature || 'free-plugin-unknown';
    feature = feature.toLowerCase();
    feature = feature.replace(' ', '-');

    return feature;
  }

  function open_upsell(feature) {
    feature = clean_feature(feature);

    $('#wpel-pro-dialog').dialog('open');

    $('#wpel-pro-table .button-buy').each(function (ind, el) {
      tmp = $(el).data('href-org');
      tmp = tmp.replace('pricing-table', feature);
      $(el).attr('href', tmp);
    });
  } // open_upsell

  if (window.localStorage.getItem('wpel_upsell_shown') != 'true') {
    open_upsell('welcome');

    window.localStorage.setItem('wpel_upsell_shown', 'true');
    window.localStorage.setItem('wpel_upsell_shown_timestamp', new Date().getTime());
  }

  if (window.location.hash == '#open-pro-dialog') {
    open_upsell('url-hash');
    window.location.hash = '';
  }

  $('a.show-link-rules').click(function () {
    $('#link-rules-new').dialog({
      height: 'auto',
      width: 'auto',
      closeOnEscape: true,
      dialogClass: 'wp-dialog',
      modal: true,
      open: function (event, ui) {
        wpel_fix_dialog_close(event, ui);
      },
    });
  });

  jQuery(document).ready(function ($) {
    $('.wpel-colorpicker').wpColorPicker();
  });

  $('.wpel-exit-confirmation').on('click', function (event) {
    event.stopPropagation();
    refresh_exit_confirmaiton_preview();
  });

  function refresh_exit_confirmaiton_preview() {
    wp_external_links = {};
    wp_external_links.background = $('#wpel-exit-confirmation-settings-background').val();
    wp_external_links.title = $('#wpel-exit-confirmation-settings-title').val();
    wp_external_links.title_color = $('#wpel-exit-confirmation-settings-title_color').val();
    wp_external_links.title_background = $('#wpel-exit-confirmation-settings-title_background').val();
    wp_external_links.title_size = $('#wpel-exit-confirmation-settings-title_size').val();
    wp_external_links.text = $('#wpel-exit-confirmation-settings-text').val().replace('{siteurl}', wpel.home_url);
    wp_external_links.text_color = $('#wpel-exit-confirmation-settings-text_color').val();
    wp_external_links.text_size = $('#wpel-exit-confirmation-settings-text_size').val();
    wp_external_links.popup_width = $('#wpel-exit-confirmation-settings-popup_width').val();
    wp_external_links.popup_height = $('#wpel-exit-confirmation-settings-popup_height').val();
    wp_external_links.overlay = $('#wpel-exit-confirmation-settings-overlay').val();
    wp_external_links.overlay_color = $('#wpel-exit-confirmation-settings-overlay_color').val();
    wp_external_links.button_text = $('#wpel-exit-confirmation-settings-button_text').val();
    wp_external_links.button_size = $('#wpel-exit-confirmation-settings-button_size').val();
    wp_external_links.button_color = $('#wpel-exit-confirmation-settings-button_color').val();
    wp_external_links.button_background = $('#wpel-exit-confirmation-settings-button_background').val();
    wp_external_links.title = $('#wpel-exit-confirmation-settings-title').val();

    wp_external_links.href = 'https://www.google.com';

    exit_confirmation_html = '';
    if (wp_external_links.overlay == '1') {
      exit_confirmation_html += '<div id="wpel_exit_confirmation_overlay"></div>';
    }
    exit_confirmation_html += '<div id="wpel_exit_confirmation">';
    if (wp_external_links.title.length > 0) {
      exit_confirmation_html += '<div id="wpel_exit_confirmation_title">' + wp_external_links.title + '</div>';
    }
    exit_confirmation_html +=
      '<div id="wpel_exit_confirmation_link">' +
      wp_external_links.text +
      '<br /><a target="' +
      wp_external_links.href +
      '" href="' +
      wp_external_links.href +
      '">' +
      wp_external_links.href +
      '</a></div>';
    exit_confirmation_html += '<div id="wpel_exit_confirmation_button_wrapper">';
    exit_confirmation_html +=
      '<div id="wpel_exit_confirmation_cancel" onMouseOver="this.style.opacity=\'0.8\'" onMouseOut="this.style.opacity=\'1\'">' +
      wp_external_links.button_text +
      '</a></div>';
    exit_confirmation_html += '</div>';
    exit_confirmation_html += '</div>';

    exit_confirmation_html += '<style>';
    exit_confirmation_html +=
      '#wpel_exit_confirmation_overlay{width:100%;height:100%;position:fixed;top:0px;left:0px;opacity:0.2;z-index:100000;background:' +
      wp_external_links.overlay_color +
      ';}';
    exit_confirmation_html +=
      '#wpel_exit_confirmation{z-index:100001;border-radius:4px;padding-bottom:40px;position:fixed;top:0px;left:0px;top:50%;left:50%;margin-top:-' +
      wp_external_links.popup_height / 2 +
      'px;margin-left:-' +
      wp_external_links.popup_width / 2 +
      'px;width:' +
      wp_external_links.popup_width +
      'px;height:' +
      wp_external_links.popup_height +
      'px;background:' +
      wp_external_links.background +
      ';}';
    exit_confirmation_html +=
      '#wpel_exit_confirmation_title{width:100%;padding:6px 10px; text-align:center; box-sizing: border-box; background:' +
      wp_external_links.title_background +
      ';font-size:' +
      wp_external_links.title_size +
      'px; color:' +
      wp_external_links.title_color +
      ';}';
    exit_confirmation_html +=
      '#wpel_exit_confirmation_link{width:100%;padding:10px 20px; line-height: 1.5; box-sizing: border-box;font-size:' +
      wp_external_links.text_size +
      'px; color:' +
      wp_external_links.text_color +
      ';}';
    exit_confirmation_html +=
      '#wpel_exit_confirmation_button_wrapper{width:100%; text-align:center; position:absolute; bottom:10px;}';
    exit_confirmation_html +=
      '#wpel_exit_confirmation_cancel{cursor:pointer;border-radius:4px;padding:10px 15px;display:inline-block;font-size:' +
      wp_external_links.button_size +
      'px;color:' +
      wp_external_links.button_color +
      '; background:' +
      wp_external_links.button_background +
      ';}';

    exit_confirmation_html += '@media only screen and (max-width: 900px) {';
    exit_confirmation_html +=
      '#wpel_exit_confirmation{ width: 90%; margin: 0 auto; padding-bottom: 40px; top: 20%; position: fixed; left: auto; height: auto; height:' +
      wp_external_links.popup_height +
      'px; display: block; margin-left: 5%;}';
    exit_confirmation_html += '}';
    exit_confirmation_html += '</style>';

    $('#exit-confirmation-preview').html(exit_confirmation_html);
  }

  $('body').on('click', '#wpel_exit_confirmation_cancel', function (e) {
    $('#wpel_exit_confirmation_overlay').remove();
    $('#wpel_exit_confirmation').remove();
  });

  $('body').click(function () {
    $('#wpel_exit_confirmation_overlay').remove();
    $('#wpel_exit_confirmation').remove();
  });

  $('#wpel_exit_confirmation').click(function (event) {
    event.stopPropagation();
  });
});

function wpel_fix_dialog_close(event, ui) {
  jQuery('.ui-widget-overlay').bind('click', function () {
    jQuery('#' + event.target.id).dialog('close');
  });
} // wpel_fix_dialog_close
