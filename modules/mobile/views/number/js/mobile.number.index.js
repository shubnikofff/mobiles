/**
 * Created by bill on 26.03.15.
 */
(function ($) {

    $.fn.mobileNumberIndexView = function(options) {

        var defaults = {
            searchForm : null,
            pjaxContainer: null,
            searchTextInput: null,
            createNumberControl: null,
            updateNumberControl: null,
            activeSearchControl: null,
            timer: null
        };

        var settings = $.extend({}, defaults, options || {});

        var initUpdateControl = function() {
            $(settings.updateNumberControl, settings.pjaxContainer).on('click', function (e) {
                e.preventDefault();
                teleport.$commonContainer.load($(this).attr('href'));
            });
        };

        var search = function(activeControl) {
            var $form = $(settings.searchForm);
            settings.activeSearchControl = activeControl;
            $.pjax({url: $form.attr('action')+'?'+$form.serialize(), container: settings.pjaxContainer});
        };

        $(settings.createNumberControl).on('click',function (e) {
            e.preventDefault();
            var $searchTextControl = $(settings.searchTextInput, settings.searchForm);
            var requestData = $.isNumeric($searchTextControl.val()) ? {number : $searchTextControl.val()} : {};
            $.get($(this).attr('href'),requestData, function(data){teleport.$commonContainer.empty().append(data)},'html');
        });

        $(settings.searchTextInput).keypress(function(e){
            if(e.which === 13) {
                $(settings.createNumberControl).trigger('click');
            }
        });

        $(':text', settings.searchForm).keyup(function (e) {
            if($.inArray(e.which,[9,13,27,37,38,39,40])===-1){
                var control = this;
                settings.timer && clearTimeout(settings.timer);
                settings.timer = setTimeout(function(){search(control);},350);
            }
        });

        $('select, input[type=radio]',settings.searchForm).on('change', function(){
            search(this);
        });
        
        $(document).on('pjax:end', function() {
            if(settings.activeSearchControl !== null) {
                $(settings.activeSearchControl).focus();
                settings.activeSearchControl = null;
            }
            initUpdateControl();
        });

        /*$(teleport.$commonContainer).on('modalView.actionDone', function () {
            $.pjax.reload(settings.pjaxContainer);
        });*/

        initUpdateControl();

        return this;
    }

})(window.jQuery);