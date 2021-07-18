jQuery(document).ready(function($) {
    var wpzoom_widget_regexp = /wpzoom|zoom_|zoom-/;

    $('.widget').filter(function () {
        return wpzoom_widget_regexp.test(this.id);
    }).addClass('wpz_widget_style');
});
