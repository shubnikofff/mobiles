/**
 * Created by bill on 16.04.15.
 */
(function ($) {

    $.fn.modalView = function (options) {

        var defaults = {
            $widget: this,
            $modal: $('div.modal', this),
            containerSelector: null,
            modalHide: null
        };

        var settings = $.extend({}, defaults, options || {});

        settings.$container = (settings.containerSelector === null) ? teleport.$commonContainer : $(settings.containerSelector);

        settings.$modal.on('hidden.bs.modal', function () {
            if (settings.modalHide !== null) {
                settings.modalHide.resolve();
            }
        });

        $('div.modal-footer button', settings.$modal).on('click', function () {
            settings.modalHide = $.Deferred();
            settings.$modal.modal('hide');
        });

        var sendRequest = function (url, params) {
            settings.modalHide.done(function () {
                $.post(url, params, 'html').done(function (data) {
                    settings.$container.empty().append(data);
                    settings.$container.trigger('modalView.actionDone');
                });
            });
        };

        $('div.modal-footer button[data-submit]', settings.$modal).on('click', function () {
            var $form = $('form', settings.$modal);
            if ($form.length > 0) {
                sendRequest($form.attr('action'), $form.serialize());
            }
        });

        $('div.modal-footer button[data-action]', settings.$modal).on('click', function () {
            sendRequest($(this).data('action'));
        });

        settings.$modal.modal('show');

        return this;
    }

})(window.jQuery);