jQuery(function($){
	var optionTypeClass = '.fw-option-type-color-palette';
	var customRadioSelector = '.predefined .fw-option-type-radio > div:last-child input[type="radio"]';

	fwEvents.on('fw:options:init', function (data) {
		var $options = data.$elements.find(optionTypeClass +':not(.initialized)');

		$options.find('.fw-option-type-color-picker').on('focus', function () {
            $(this).closest(optionTypeClass).find('.fw-palette').removeClass('fw-palette-border')

			// check "custom" radio box
			$(this).closest(optionTypeClass).find(customRadioSelector).prop('checked', true);
		});

		$options.find(customRadioSelector).on('focus', function () {
			$(this).closest(optionTypeClass).find('.custom input').focus();
		});

		$options.addClass('initialized');

        var $predifined_container = $(optionTypeClass).children('.predefined');

        //add checked border to palette
        $predifined_container.find('.fw-palette').each(function(){
            if($(this).next().is(':checked'))
                $(this).addClass('fw-palette-border');
        });

        //if one of the palette's element is cliked
        $predifined_container.find('label').on('click',function(){
            $(this).parents('.fw-option.fw-option-type-radio').find('.fw-palette').removeClass('fw-palette-border');

            //add border to cicked element
            $(this).find('.fw-palette').addClass('fw-palette-border');
        });

        //if not a palette element clicked , then remove all borders
        $predifined_container.children('.fw-option.fw-option-type-radio').children('div:last-child').find('label').on('click',function(){
            $(this).parents('.fw-option.fw-option-type-radio').find('.fw-palette').removeClass('fw-palette-border');
        });
	});
});