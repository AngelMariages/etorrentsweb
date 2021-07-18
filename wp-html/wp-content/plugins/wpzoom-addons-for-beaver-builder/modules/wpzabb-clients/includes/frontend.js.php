(function($) {

	// Clear the controls in case they were already created.
	$('.fl-node-<?php echo $id; ?> .fl-slider-next').empty();
	$('.fl-node-<?php echo $id; ?> .fl-slider-prev').empty();

	// Create the slider.
	$('.fl-node-<?php echo $id; ?> .wpzabb-clients').bxSlider({
		autoStart : <?php echo $settings->auto_play ?>,
		auto : true,
		adaptiveHeight: true,
		pause : <?php echo $settings->pause * 1000; ?>,
		mode : '<?php echo $settings->transition; ?>',
		autoDirection: '<?php echo $settings->direction; ?>',
		speed : <?php echo $settings->speed * 1000;  ?>,
		pager : <?php echo $settings->dots; ?>,
		minSlides: 1,
		maxSlides: 5,
		slideWidth: 258,
		slideMargin: 20,
		infiniteLoop: true,
		shrinkItems: true,
		nextSelector : '.fl-node-<?php echo $id; ?> .fl-slider-next',
		prevSelector : '.fl-node-<?php echo $id; ?> .fl-slider-prev',
		nextText: '<i class="fa fa-angle-right"></i>',
		prevText: '<i class="fa fa-angle-left"></i>',
		controls : <?php echo $settings->arrows; ?>,
		onSliderLoad: function() {
			$('.fl-node-<?php echo $id; ?> .wpzabb-clients').addClass('wpzabb-clients-loaded');
		},
		onSliderResize: function(currentIndex){
			this.working = false;
			this.reloadSlider();
		},
		onSlideBefore: function(ele, oldIndex, newIndex) {
			$('.fl-node-<?php echo $id; ?> .fl-slider-next a').addClass('disabled');
			$('.fl-node-<?php echo $id; ?> .fl-slider-prev a').addClass('disabled');
			$('.fl-node-<?php echo $id; ?> .bx-controls .bx-pager-link').addClass('disabled');
		},
		onSlideAfter: function( ele, oldIndex, newIndex ) {
			$('.fl-node-<?php echo $id; ?> .fl-slider-next a').removeClass('disabled');
			$('.fl-node-<?php echo $id; ?> .fl-slider-prev a').removeClass('disabled');
			$('.fl-node-<?php echo $id; ?> .bx-controls .bx-pager-link').removeClass('disabled');
		},
	});

})(jQuery);
