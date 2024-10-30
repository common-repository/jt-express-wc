(function ($) {
    'use strict';

    $(function () {
        var basicPickr = $('.flatpickr-basic');
        if (basicPickr.length) {
            basicPickr.flatpickr(
                {
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    disable: [
                        date => (date.getDay() === 0 || handleSaturdayFrom9To11(date)),
                        "2021-07-20",
                        "2021-08-09",
                        "2021-11-04",
                        "2021-12-25",
                    ],
                    locale: {
                        "firstDayOfWeek": 1
                    }
                }
            );
        }
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
        if (typeof jQuery.validator === 'function') {
            jQuery.validator.setDefaults({
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    if (
                        element.parent().hasClass('input-group') ||
                        element.hasClass('select2') ||
                        element.attr('type') === 'checkbox'
                    ) {
                        error.insertAfter(element.parent());
                    } else if (element.hasClass('custom-control-input')) {
                        error.insertAfter(element.parent().siblings(':last'));
                    } else {
                        error.insertAfter(element);
                    }

                    if (element.parent().hasClass('input-group')) {
                        element.parent().addClass('is-invalid');
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('error');
                    if ($(element).parent().hasClass('input-group')) {
                        $(element).parent().addClass('is-invalid');
                    }
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('error');
                    if ($(element).parent().hasClass('input-group')) {
                        $(element).parent().removeClass('is-invalid');
                    }
                }
            });
        }
        function handleSaturdayFrom9To11(date) {
            var currentTime = new Date();
            var isSaturday = date.getDay() === 6;
            return isSaturday && (currentTime.getDate() === date.getDate()) && (currentTime.getHours() > 11);
        }
    });

    $(window).load(function () {

    });

})(jQuery);
