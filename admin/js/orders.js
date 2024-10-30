(function ($) {
    'use strict';

    $(function () {

        // Remove 2 fileds to serve search function
        // debugger;
        $('[name=_wp_http_referer]').remove();
        $('#_wpnonce').remove();
        var pickupTimeForm = $('#pickup-timing-form');

        if (pickupTimeForm.length) {
            pickupTimeForm.validate({
                rules: {
                    'contact-name': {
                        required: true
                        //email: true
                    },
                    'email': {
                        required: true,
                        email: true
                    },
                    'phone-number': {
                        required: true
                    },
                    'post-code': {
                        required: true,
                        maxlength: 6
                    },
                    'address': {
                        required: true,
                    },
                    'parcel-length': {
                        required: true,
                    },
                    'parcel-width': {
                        required: true,
                    },
                    'parcel-height': {
                        required: true,
                    },
                    'parcel-weight': {
                        required: true,
                    },
                    'parcel-value': {
                        required: true,
                    },
                    'pickup-date': {
                        required: true,
                    },
                    'pickup-time-slot': {
                        required: true,
                    },
                    'pickup-note': {
                        required: true,
                    }
                }
            });
        }

        function handleLoading(form, state) {
            var button = form.find('button[type="submit"]');
            var spinner = button.find('span').first();
            var buttonText = button.find('span').last();

            if (state == 'loading') {
                button.attr('disabled', true);
                spinner.removeClass('d-none');
                buttonText.text('Loading...');
                buttonText.addClass('ml-25');
                return;
            }

            button.removeAttr('disabled');
            spinner.addClass('d-none');
            buttonText.text('Connect');
            buttonText.removeClass('ml-25');

        }

        $('.btn-open-modal').click(function (e) {
            $('#schedule-pick-up').attr("data-id", $(this).data('id'));
            $('#parcel-weight').val($(this).data('weight'));
            $('#parcel-details').show();
        });

        $(pickupTimeForm.find('.close')).click(function (e) {
            pickupTimeForm.validate().resetForm();
            pickupTimeForm.trigger("reset");

        });

        $(pickupTimeForm).submit(function (e) {
            e.preventDefault();
            var form = $(this);
            if (!form.valid()) return;

            var date = $('#pickup-date').val();
            var timeSlot = $('#pickup-time-slot').val();
            var orderId = $('#schedule-pick-up').data('id');
            var from = timeSlot.split('-')[0].trim();
            var to = timeSlot.split('-')[1].trim();

            var timeWindow = {
                from: from,
                to: to
            };
            var pickUpNote = $('#pickup-note').val();

            var length = $('#parcel-length').val();
            var width = $('#parcel-width').val();
            var height = $('#parcel-height').val();
            var weight = $('#parcel-weight').val();
            var itemValue = $('#parcel-value').val();
            var contactName = $('#contact-name').val();
            var phoneNumber = $('#phone-number').val();
            var email = $('#email').val();
            var postCode = $('#post-code').val();
            var address = $('#address').val();

            var data = {
                timeWindow: timeWindow,
                pickUpNote: pickUpNote,
                orderId: orderId,
                length: length,
                width: width,
                height: height,
                weight: weight,
                contactName: contactName,
                phoneNumber: phoneNumber,
                email: email,
                postCode: postCode,
                address: address,
                date: date,
                itemValue: itemValue
            };

            handleLoading(form, 'loading');
            $('#order-error').hide();
            $.ajax({
                type: "POST",
                url: 'admin.php?page=jt-woocommerce&tab=orders&action=create',
                data: JSON.stringify(data),
                contentType: 'application/json',
                dataType: 'text'
            }).then(function (res) {
                var lastLn = res.lastIndexOf('\n');
                var res2 = res.substring(lastLn);
                var jsonRes = JSON.parse(res2).data;
                var trackingIds = Object.keys(jsonRes);

                if (trackingIds.length > 1) {
                    $.ajax({
                        type: "POST",
                        url: multipleConnoteUrl,
                        headers: {
                            Authorization: 'JWT ' + token
                        },
                        contentType: 'application/json',
                        data: JSON.stringify({
                            ids: trackingIds,
                            style: 'A6'
                        }),
                    }).done(function (data) {
                        if (data.connote_label_url) {
                            window.open(data.connote_label_url, '_blank');
                            window.location.reload();
                        }
                    });
                    return;
                }

                if (trackingIds.length === 1) {
                    $.ajax({
                        type: "GET",
                        url: connoteURL + "/" + trackingIds[0] + "/a6",

                        headers: {
                            Authorization: 'JWT ' + token
                        }
                    }).done(function (data) {
                        setTimeout(function () {
                            if (data.connote_label_url) {
                                handleLoading(form);
                                window.open(data.connote_label_url, '_blank');
                                window.location.reload();
                            }
                        }, 2000);
                    });
                } else {
                    $('#order-error span').html("There are some error now. Please reload the page.");
                    $('#order-error').show();
                }

            }).fail(function (err) {
                //console.error(err);
                handleLoading(form);
                var response = err && err.responseText;
                var sign = 'ERROR:';
                var lastLn = response.lastIndexOf(sign);
                var error = response.substring(lastLn + sign.length);
                var message = JSON.parse(error).data.message;
                $('#order-error span').html(message);
                $('#order-error').show();
            });

        });

        $('a[action="label-print"]').click(function (e) {
            var tracking = $(this).attr('uid');
            var format = $(this).attr('format');
            $.ajax({
                type: "GET",
                url: connoteURL + "/" + tracking + "/" + format,
                headers: {
                    Authorization: 'JWT ' + token
                }
            }).done(function (data) {
                if (data.connote_label_url) {
                    window.open(data.connote_label_url, '_blank');
                }
            });
        });

        $('a[action="cancel"]').click(function (e) {
            var confirm = window.confirm("Do you want to cancel the order?");
            if (!confirm) {
                return true;
            }
            var id = $(this).attr('uid');
            $.ajax({
                type: "GET",
                url: 'admin.php?page=jt-woocommerce&tab=orders&action=cancel&id=' + id,
            }).done(function (data) {
                window.location.reload();
            });
        });

        $('.jt-page-content #doaction').unbind('click');
        $('.jt-page-content #doaction').bind('click', function (e) {

            e.preventDefault();
            var action = $('#bulk-action-selector-top').val();
            var ids = [];
            $('input[name="order[]"]:checked').each(function () {
                ids.push($(this).val());
            });

            if (ids.length == 0) {
                return;
            }

            if (action == 'print') {
                var trackingIds = [];
                $('.tracking_id').each(function () {
                    trackingIds.push($(this).text());
                });

                $.ajax({
                    type: "POST",
                    url: multipleConnoteUrl,
                    headers: {
                        Authorization: 'JWT ' + token
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        ids: trackingIds,
                        style: 'A6'
                    }),
                }).done(function (data) {
                    if (data.connote_label_url) {
                        window.open(data.connote_label_url, '_blank');
                        window.location.reload();
                    }
                });

            } else if (action == 'schedule_pickup') {
                $('#schedule-pick-up').modal('show');
                $('#schedule-pick-up').attr("data-id", ids.join(','));
                $('#parcel-details').hide();
            }

        });

        $("#pickup-date").change(function () {
            var timeSlotEle = $("#pickup-time-slot");
            var timeSlotFromMontoFri = "<option selected='selected' value='9-18'>09:00 - 18:00</option><option value='9-12'>09:00 - 12:00</option><option value='12-15'>12:00 - 15:00</option><option value=15-18>15:00 - 18:00</option>";
            var timeSlotSaturday = "<option selected='selected' value='9-13'>09:00 - 13:00</option>";
            if (subTab === 'create-return-order') {
                timeSlotFromMontoFri = "<option selected='selected' value='9-18'>09:00 - 18:00</option>";
            }
            var pickupDate = new Date($(this).val());
            if (pickupDate) {
                if (pickupDate.getDay() === 6) {
                    timeSlotEle.html(timeSlotSaturday);
                    return;
                }
                timeSlotEle.html(timeSlotFromMontoFri);
            }


        });
    });
})(jQuery);