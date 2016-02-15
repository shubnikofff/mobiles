/**
 * Created by bill on 15.04.15.
 */
(function ($) {

    $.fn.containerLoader = function (options) {

        var defaults = {
            containerSelector : null,
            linkSelector : null,
            $container : teleport.$commonContainer
        };

        var settings = $.extend({}, defaults, options || {});

        if(settings.containerSelector !== null) {
            settings.$container = $(settings.containerSelector);
        }

        $(settings.linkSelector,this).on('click',function(e){
            if($(this).data('containerLoader') !== 0) {
                e.preventDefault();
                settings.$container.load(this.href);
            }
        });
        
        return this;
    }

})(window.jQuery);