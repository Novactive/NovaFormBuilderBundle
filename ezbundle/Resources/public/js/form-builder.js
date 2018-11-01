(function ($) {
    'use strict';

    var $document = $(document);

    $(function () {
        $('.js-form-fields-collection').each(function () {
            var $container = $(this);
            var $addButton = $('.js-form-builder__add-new-field', $container);
            var $selectFieldType = $('.js-form-builder__select-field-type', $container);
            var $collectionHolder = $('.js-form-fields-collection-entries', $container);
            var fieldsCount = $collectionHolder.children().length;
            var $deleteBtns = $('.js-form-fields__delete-entry');

            $.each($('.js-form-fields-choices-collection'), function () {
                var $itemsCount = $(this).find('.js-form-fields-choice-item').length;
                $(this).data('items-count', $itemsCount);
            });

            $(document).on('click', '.js-form-field-choices__add-choice', function (e) {

                var choicesHolder = $(this).closest('.js-form-fields-choices-collection');
                var choicesCount = choicesHolder.data('items-count');

                e.preventDefault();
                var prototype = choicesHolder
                    .find('fieldset div')
                    .data('prototype');

                var fieldForm = prototype.replace(/__choice_name__/g, choicesCount + 1);
                $('.mb-3.card .js-form-field-row').find('.js-form-field-choices__delete-item');
                choicesHolder.children('fieldset').children('div').append(fieldForm);
                choicesHolder.data('items-count', choicesCount + 1);
            });

            $(document).on('click', '.js-form-field-choices__delete-item', function (e) {
                $(this).closest('.js-form-fields-choice-item').remove();
            });

            $addButton.click(function (e) {
                e.preventDefault();
                var prototype = $(':selected', $selectFieldType).data('prototype');
                var fieldForm = prototype.replace(/__name__/g, fieldsCount);
                $collectionHolder.append(fieldForm);
                $document.trigger('form-builder:field-added');
                fieldsCount++;
                $('.js-form-fields__delete-entry:eq('+(fieldsCount - 1)+')').click(function() {
                    $(this).closest('.card').remove();
                });
            });

            $deleteBtns.click(function (e) {
                e.preventDefault();

                $(this).closest('.card').remove();
            });
        });
    });
})(jQuery);
