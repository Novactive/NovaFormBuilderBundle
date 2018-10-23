(function ($) {
    'use strict';
    $(function () {
        var $rows = $('.js-form-field-row');
        var weightSelector = '[name$="weight]"]';
        var $fieldCollection = $('.js-form-fields-collection-entries');
        var $document = $(document);

        $document.on('form-builder:field-added', function () {
            setWeights($('.js-form-field-row'), weightSelector);
        });

        $fieldCollection.sortable({
            update: function(event, ui) {
                var $elem = ui.item;

                setWeights($('.js-form-field-row'), weightSelector);
            },
            placeholder: 'formbuilder__sort-placeholder',
            forcePlaceholderSize: true
        });
    });

    function setWeights($elems, weightSelector) {
        $elems.each(function(index) {
            $(this).find(weightSelector).val(index);
        });
    }

})(jQuery);