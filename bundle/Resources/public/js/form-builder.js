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

            $(document).on('click', '.js-form-field-choices__add-choice', function (e) {

                var choicesHolder = $(this).closest('.js-form-fields-choices-collection');
                var choicesCount = choicesHolder.find('div .form-group').length + 1;

                e.preventDefault();
                var prototype = choicesHolder
                    .find('fieldset div')
                    .data('prototype');

                var fieldForm = prototype.replace(/__choice_name__/g, choicesCount);
                choicesHolder.children('fieldset').children('div').append(fieldForm);
            });

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