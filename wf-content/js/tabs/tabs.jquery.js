/*
Moreganic Tabs for JQuery
Branched for Wonderflux from Organic Tabs by Chris Coyer - http://css-tricks.com/
Permission to use and distribute with Wonderflux was granted on 25th May 2011, thanks Chris;) 
PARAMS
"speed" (200) - speed of animation
"height_add" (0) - add extra height to deal with unusual content/CSS that screws up height detection on animation
*/
jQuery.noConflict();
jQuery(document).ready(function($) {

    $.moreganicTabs = function(el, options) {

        var base = this;
        base.$el = $(el);
        base.$tabsnav = base.$el.find(".tabsnav");

        base.init = function() {

            base.options = $.extend({},$.moreganicTabs.defaultOptions, options);

            // Accessible hiding fix
            $(".hide").css({
                "position": "relative",
                "top": 0,
                "left": 0,
                "display": "none"
            });

            base.$tabsnav.delegate("li > a", "click", function() {

                // Figure out current list via CSS class
                var curList = base.$el.find("a.current").attr("href").substring(1),

                // List moving to
                    $newList = $(this),

                // Figure out ID of new list
                    listID = $newList.attr("href").substring(1),

                // Set outer wrapper height to (static) height of current inner list
                    $allListWrap = base.$el.find(".list-wrap"),
                    curListHeight = $allListWrap.height();
                $allListWrap.height(curListHeight);

                if ((listID != curList) && ( base.$el.find(":animated").length == 0)) {

                    // Fade out current list
                    base.$el.find("#"+curList).fadeOut(base.options.speed, function() {

                        // Fade in new list on callback
                        base.$el.find("#"+listID).fadeIn(base.options.speed);

                        // Adjust outer wrapper to fit new list with adjustment if required

                        var newHeight = base.$el.find("#"+listID).height();

                        // Add optional height_add param to adjust fit nicely
                        if (base.options.height_add > 0) {
                        	newHeight = newHeight + base.options.height_add;
                        }

                        $allListWrap.animate({
                            height: newHeight
                        });

                        // Remove highlighting - Add to just-clicked tab
                        base.$el.find(".tabsnav li a").removeClass("current");
                        $newList.addClass("current");

                    });

                }

                // Don't behave like a regular link
                // Stop propegation and bubbling
                return false;
            });

        };
        base.init();
    };

    $.moreganicTabs.defaultOptions = {
        "speed": 300,
        "height_add": 0
    };

    $.fn.moreganicTabs = function(options) {
        return this.each(function() {
            (new $.moreganicTabs(this, options));
        });
    };

});