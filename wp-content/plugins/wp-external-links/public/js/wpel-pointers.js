/*
 * WP External Links
 * Backend GUI pointers
 * (c) WebFactory Ltd, 2017 - 2022
 */

jQuery(document).ready(function ($) {
  if (typeof wpel_pointers == 'undefined') {
    return;
  }

  $.each(wpel_pointers, function (index, pointer) {
    if (index.charAt(0) == '_') {
      return true;
    }
    $(pointer.target)
      .pointer({
        content: '<h3>WP External Links</h3><p>' + pointer.content + '</p>',
        pointerWidth: 380,
        position: {
          edge: pointer.edge,
          align: pointer.align,
        },
        close: function () {
          $.get(ajaxurl, {
            notice_name: index,
            _ajax_nonce: wpel_pointers._nonce_dismiss_pointer,
            action: 'wpel_dismiss_notice',
          });
        },
      })
      .pointer('open');
  });
});
