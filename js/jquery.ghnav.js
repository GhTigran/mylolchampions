(function($) {

    $.fn.ghNav = function(options) {

        options = $.extend({
            speed : 500,
            reset : 0,
            easing : 'easeOutExpo'
        }, options);

        return this.each(function() {
            var nav = $(this),
                currentPageItem,
                highlighter,
                reset,
                returnPoint;

            if($('#selected').length) {
                currentPageItem = $('#selected', nav);
            } else {
                currentPageItem = 0;
            }

            if(currentPageItem != 0) {
                $('<li id="nav-highlighter"><div class="left"></div></li>').css({
                    width : currentPageItem.outerWidth(),
                    left : currentPageItem.position().left,
                    top : currentPageItem.position().top
                }).appendTo(this);

                highlighter = $('#nav-highlighter', nav);
            }

            $('li:not(#nav-highlighter)', nav).hover(function() {
                // mouse over
                clearTimeout(reset);
                if(currentPageItem != 0) {
                    highlighter.animate(
                        {
                            left : $(this).position().left,
                            width : $(this).outerWidth()
                        },
                        {
                            duration : options.speed,
                            easing : options.easing,
                            queue : false
                        }
                    );
                } else {
                    $('<li id="nav-highlighter"><div class="left"></div></li>').css({
                        width : $(this).outerWidth(),
                        left : $(this).position().left,
                        top : $(this).position().top
                    }).appendTo(this);

                    highlighter = $('#nav-highlighter', nav);
                    currentPageItem = 1;
                }
            }, function() {
                // mouse out
                reset = setTimeout(function() {
                    if(currentPageItem != 1) {
                        highlighter.animate({
                            width : currentPageItem.outerWidth(),
                            left : currentPageItem.position().left
                        }, options.speed)
                    } else {
                        highlighter.remove();
                        currentPageItem = 0;
                    }
                }, options.reset);

            });

        }); // end each

    };

})(jQuery);