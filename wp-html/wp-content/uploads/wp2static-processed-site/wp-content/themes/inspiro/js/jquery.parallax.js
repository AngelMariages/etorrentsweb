/*
Plugin: jQuery Parallax
Version 1.1.5
Author: ADG Idea Alessandro del Gobbo (based on code of Ian Lunn)
Twitter: @alexoverflow
Author URL: http://www.adg-idea.com/
Project on GitHub: https://github.com/aledelgo/jQuery-Parallax

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

// USAGE:
// .parallax(xPosition, speedFactor, outerHeight) options:
//xPosition - Horizontal position of the element
//yPosition - Vertical initial position of the element (IN PIXELS!!!)
//inertia - speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling
//outerHeight (true/false) - Whether or not jQuery should use it's outerHeight option to determine when a section is in the viewport
/*$('#intro').parallax("50%", null, 0.1);
 $('#second').parallax("50%", 30, 0.1);*/

(function( $ ){
	var $window = $(window),
		windowHeight;

	$window.resize(function () {
		windowHeight = $(this).height();
	}).triggerHandler('resize');

	$.fn.parallax = function(xpos, ypos, speedFactor, outerHeight) {
		var $this = $(this),
			getHeight,
			firstTop;

		// setup defaults if arguments aren't specified
		xpos = xpos || "50%";
		ypos = ypos || 0;
		speedFactor = speedFactor || 0.5;

		if (typeof outerHeight === 'undefined')  {
			outerHeight = true;
		}

		if (outerHeight) {
			getHeight = function(jqo) {
				return jqo.outerHeight(true);
			};
		} else {
			getHeight = function(jqo) {
				return jqo.height();
			};
		}

		//get the starting position of each element to have parallax applied to it
		$window.bind('scroll resize', function (){
			var pos = $window.scrollTop();

			$this.each(function(){
				var $element = $(this),
					top = $element.offset().top,
					height = getHeight($element);

				firstTop = $this.offset().top;

				// Check if totally above or totally below viewport
				if (top + height < pos || top > pos + windowHeight) {
					return;
				}

				$this.css('backgroundPosition', xpos + " " + Math.round(ypos + (firstTop - pos) * speedFactor) + "px");
			});
		}).triggerHandler('scroll');
	};
})(jQuery);
