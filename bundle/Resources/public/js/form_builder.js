(function($) {
    'use strict';
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
            fieldsCount++;
        });

        $deleteBtns.click(function (e) {
            e.preventDefault();

            $(this).closest('.card').remove();
        });

        // sorting
        var $rows = $('.js-form-field-row');
        $rows.parent().sortable({
            update: function(event, ui) {
                var $elem = ui.item;

                $('.js-form-field-row').each(function(index) {
                    $(this).find('[name$="weight]"]').val(index);
                })
            },
            placeholder: 'formbuilder__sort-placeholder',
            forcePlaceholderSize: true
        });
    });
})(jQuery);