(function ($) {
    'use strict';

    var $document = $(document);

    $('.nav-item.last:first a').tab('show');

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
                inputNumberEvents();
                fieldsCount++;
                $('.js-form-fields__delete-entry:eq(' + (fieldsCount - 1) + ')').click(function () {
                    $(this).closest('.card').remove();
                });
            });

            $deleteBtns.click(function (e) {
                e.preventDefault();

                $(this).closest('.card').remove();
            });

            inputNumberEvents();
        });
    }

    function inputNumberEvents() {
        var $inputNumbers = $("input[type='number']");
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
        var $submissionsUnlimited = $("input[type='checkbox']").filter("[name*='[submissionsUnlimited]']");
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

    $(function () {
        init();

        // Edit Custom Form on Content Edit page
        var $editCustomForm = $('#edit_custom_form');
        if ($editCustomForm.length > 0) {

            $editCustomForm.on('show.bs.modal', function () {
                var $modalBody = $editCustomForm.find('.modal-body');
                if ($modalBody.html() === '') {
                    $.post($editCustomForm.data('endpoint'), function (data) {
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
