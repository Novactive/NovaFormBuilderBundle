(function ($) {
    'use strict';

    var $document = $(document);

    function init() {
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
                    .find('> div:not(.js-form-fields-choice-header)')
                    .data('prototype');

                var fieldForm = prototype.replace(/__choice_name__/g, choicesCount + 1);
                $('.mb-3.card .js-form-field-row').find('.js-form-field-choices__delete-item');
                choicesHolder.children('div:not(.js-form-fields-choice-header)').append(fieldForm);
                choicesHolder.find('.js-form-fields-choice-item input').filter("[name*='[weight]']").last().val(choicesCount + 1);
                choicesHolder.data('items-count', choicesCount + 1);
            });

            $(document).on('click', '.js-form-field-choices__delete-item', function (e) {
                $(this).closest('.js-form-fields-choice-item').remove();
            });

            function compare( a, b ) {
                if ( a.sorting_field < b.sorting_field ){
                    return -1;
                }
                if ( a.sorting_field > b.sorting_field ){
                    return 1;
                }
                return 0;
            }

            $(document).on('click', '.js-choice-item-ordering', function (e) {
                var field = $(this).data('field')
                var choicesHolder = $(this).closest('.js-form-fields-choices-collection').find('.js-form-fields-choice-item').first().parent();
                var itemsObj = [];
                choicesHolder.find('.js-form-fields-choice-item').each(function (index) {
                    var element = $(this);
                    element.data("key", index);
                    var sortingField = $(this).find(`[name*='[${field}]']`).val();
                    itemsObj.push({key:index, sorting_field: sortingField.toLowerCase(), element: element})
                })
                itemsObj.sort( compare );
                choicesHolder.find('.js-form-fields-choice-item').remove()
                itemsObj.forEach(function (item) {
                    choicesHolder.append(item.element)
                })
            });

            $addButton.click(function (e) {
                e.preventDefault();
                var prototype = $(':selected', $selectFieldType).data('prototype');
                var fieldForm = prototype.replace(/__name__/g, fieldsCount);
                $collectionHolder.append(fieldForm);
                $document.trigger('form-builder:field-added');
                inputEvents();
                fieldsCount++;
                $('.js-form-fields__delete-entry:eq(' + (fieldsCount - 1) + ')').click(function () {
                    $(this).closest('.card').remove();
                });
            });

            $deleteBtns.click(function (e) {
                e.preventDefault();

                $(this).closest('.card').remove();
            });

            inputEvents();

            fieldSorting();
        });
    }

    function inputEvents() {
        var $inputNumbers = $("input[type='number']");
        var $inputCheckboxes = $("input[type='checkbox']");
        $inputNumbers.filter("[name*='[minLength]'],[name*='[maxLength]'],[name*='[min]'],[name*='[max]']").on('blur', function () {
            if ($(this).val() === '') {
                $(this).val('0');
            }
        });
        $inputNumbers.filter("[name*='[minLength]'],[name*='[maxLength]'],[name*='[min]'],[name*='[max]']").on('keypress', function (e) {
            if (e.keyCode === 13 && $(this).val() === '') {
                $(this).val('0');
            }
        });

        var $submissionsUnlimited = $inputCheckboxes.filter("[name*='[submissionsUnlimited]']");
        var $maxSubmissions = $inputNumbers.filter("[name*='[maxSubmissions]']");
        if ($submissionsUnlimited.length > 0 && $submissionsUnlimited.prop('checked')) {
            $maxSubmissions.prop('disabled', true);
            $maxSubmissions.addClass('bg-grey');
        }
        $submissionsUnlimited.change(function () {
            $maxSubmissions.prop('disabled', this.checked);
            if (this.checked) {
                $maxSubmissions.addClass('bg-grey');
            } else {
                $maxSubmissions.removeClass('bg-grey');
            }
        });
    }

    function fieldSorting() {
        var weightSelector = '[name$="weight]"]';
        var $fieldCollection = $('.js-form-fields-collection-entries');
        var $document = $(document);

        $document.on('form-builder:field-added', function () {
            setWeights($('.js-form-field-row'), weightSelector);
        });

        $fieldCollection.sortable({
            update: function (event, ui) {
                setWeights($('.js-form-field-row'), weightSelector);
            },
            placeholder: 'formbuilder__sort-placeholder',
            forcePlaceholderSize: true
        });
    }

    function setWeights($elems, weightSelector) {
        $elems.each(function (index) {
            $(this).find(weightSelector).val(index);
        });
    }

    $(function () {
        init();

        // Edit Custom Form on Content Edit page
        var $editCustomForm = $('#edit_custom_form');
        if ($editCustomForm.length > 0) {

            $editCustomForm.on('show.bs.modal', function () {
                var $modalBody = $editCustomForm.find('.modal-body');
                if ($modalBody.html().indexOf('submit') < 0) {
                    $.post($editCustomForm.data('endpoint'), function (data) {
                        $modalBody.find('.edit-form-preloader').hide();
                        $modalBody.append(data);
                        init();
                        $modalBody.find('form').submit(function (e) {
                            var form = $(this);
                            var url = $editCustomForm.data('endpoint');

                            $.ajax({
                                type: "POST",
                                url: url,
                                data: form.serialize(),
                                success: function (data) {
                                    if (data.success === undefined) {
                                        var regex = /(<form[^>]+>|<form>|<\/form>)/g;
                                        var formContent = data.replace(regex, '');
                                        $modalBody.find('form').html(formContent);
                                    } else {
                                        $(".ez-field-edit--ezcustomform input").filter("[name*='[formId]']").val(data.id);
                                        $('#attached-form').html(data.name);
                                        $editCustomForm.modal('hide');
                                    }
                                }
                            });
                            e.preventDefault();
                        });

                    });
                }
            });

            $('.remove-form', $editCustomForm).click(function () {
                $.get($(this).data('endpoint'), function (data) {
                    $(".ez-field-edit--ezcustomform input").filter("[name*='[formId]']").val(null);
                    $('#attached-form').html('');
                    $editCustomForm.modal('hide');
                });
            });

        }

    });
})(jQuery);
