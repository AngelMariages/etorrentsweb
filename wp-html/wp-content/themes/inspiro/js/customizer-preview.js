(function ($, rules, _) {

    $(document).ready(function () {
        var styleSheet = $('#inspiro-custom-css').length ? $('#inspiro-custom-css')[0].sheet : undefined;

        _.each(rules['color-rules'], function (current) {
            wp.customize(current.id, function (value) {

                value.bind(function (newval) {
                    var myObj = {};
                    myObj[current.rule] = newval;
                    vein.inject(
                        current.selector.split(','),
                        myObj,
                        {'stylesheet': styleSheet}
                    );
                });
            });
        });

        _.each(rules['font-rules'], function (current) {
            wp.customize(current.id, function (value) {

                value.bind(function (newval) {
                    var myObj = {};
                    myObj[current.rule] = newval;
                    vein.inject(
                        current.selector.split(','),
                        myObj,
                        {'stylesheet': styleSheet}
                    );
                });
            });
        });

        //For font-size and font-family rules.
        _.each(['font-size', 'font-family'], function (rule) {
            _.each(rules['font-extra-rules'], function (current) {
                wp.customize(rule + '-' + current.id, function (value) {
                    value.bind(function (newval) {
                        var myObj = {};
                        myObj[rule] = (rule === 'font-size') ? newval + 'px' : newval;
                        if (rule === 'font-family') {
                            WebFont.load({
                                google: {
                                    families: [newval]
                                },
                                fontactive: function () {
                                    vein.inject(
                                        current.selector.split(','),
                                        myObj,
                                        {'stylesheet': styleSheet}
                                    );
                                }
                            });
                        } else {
                            vein.inject(
                                current.selector.split(','),
                                myObj,
                                {'stylesheet': styleSheet}
                            );
                        }

                    });
                });
            });
        });

    });
})(jQuery, inspiro_css_rules, _, vein);