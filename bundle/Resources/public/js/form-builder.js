(function($) {
    'use strict';

    var $document = $(document);

    $(function() {
        $('.js-form-fields-collection').each(function() {
            var $container = $(this);
            var $addButton = $('.js-form-builder__add-new-field', $container);
            var $selectFieldType = $('.js-form-builder__select-field-type', $container);
            var $collectionHolder = $('.js-form-fields-collection-entries', $container);
            var fieldsCount = $collectionHolder.children().length;
            var $deleteBtns = $('.js-form-fields__delete-entry');

            $addButton.click(function (e) {
                e.preventDefault();
                var prototype = $(':selected', $selectFieldType).data('prototype');
                var fieldForm = prototype.replace(/__name__/g, fieldsCount);
                $collectionHolder.append(fieldForm);
                $document.trigger('form-builder:field-added');
                fieldsCount++;
            });

            $deleteBtns.click(function (e) {
                e.preventDefault();

                $(this).closest('.card').remove();
            });
        });


    });
})(jQuery);