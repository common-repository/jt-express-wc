(function ($) {
	'use strict';
	$('a[action="cancel-return-order"]').click(function (e) {
		var confirm = window.confirm("Do you want to cancel the order?");
		if (!confirm) {
			return true;
		}
		var id = $(this).attr('uid');
		$.ajax({
			type: "GET",
			url: 'admin.php?page=jt-woocommerce&tab=orders&action=return-order-cancel&id=' + id,
		}).done(function (data) {
			window.location.reload();
		});
	});

	$(function () {
		var CURRENT_COUNTRY = 'Singapore';
		var horizontalWizard = document.querySelector('.horizontal-wizard-example');
		var bsStepper = document.querySelectorAll('.bs-stepper');

		function handleLoading(button, state) {
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

		if (typeof bsStepper !== undefined && bsStepper !== null) {
			for (var el = 0; el < bsStepper.length; ++el) {
				bsStepper[el].addEventListener('show.bs-stepper', function (event) {
					var index = event.detail.indexStep;
					var numberOfSteps = $(event.target).find('.step').length - 1;
					var line = $(event.target).find('.step');

					// fill full info to the last step
					if (index === 4) {
						var orderDetailsObj = "<p>Service Type: <strong>".concat($('#service_type').val(), "</strong></p><p>Order Reference: <strong>").concat($('#order_reference').val(), "</strong></p>");
						var itemDetailsObj = "<p>Length (cm): <strong>".concat([$('#length').val(), $('#weight_unit').val()].join(" "), "</strong></p><p>Width (cm): <strong>").concat([$('#width').val(), $('#weight_unit').val()].join(" "), "</strong></p><p>Height (cm): <strong>").concat([$('#height').val(), $('#weight_unit').val()].join(" "), "</strong></p><p>Weight: <strong>").concat([$('#weight').val(), $('#weight_unit').val()].join(" "), "</strong></p><p>Description: <strong>").concat($('#description').val(), "</strong></p>");
						var senderDetailsObj = "<p>Name: <strong>".concat($('#contact_name').val(), "</strong></p><p>Contact Number: <strong>+65").concat($('#contact_phone').val(), "</strong></p><p>Contact Email: <strong>").concat($('#contact_email').val(), "</strong></p><p>Delivery Address: <strong>\t").concat([$('#street_name').val(), [$('#unit').val(), $('#step-3#building_name').val()].join(" "), $('#postal_code').val(), CURRENT_COUNTRY].join(', '), "</strong></p><p>Collection Date: <strong>").concat($('#pickup-date').val(), "</strong></p><p>Time slot: <strong>").concat($('#pickup-time-slot').val(), "</strong></p><p>Item description: <strong>").concat($('#pickup-note').val(), "</strong></p>");
						var recipientDetailsObj = "<p>Name: <strong>".concat($('#consignee_name').val(), "</strong></p><p>Contact Number: <strong>+65").concat($('#consignee_phone').val(), "</strong></p><p>Contact Email: <strong>").concat($('#consignee_email').val(), "</strong></p><p>Delivery Address: <strong>\t").concat([$('#consignee_street_name').val(), [$('#consignee_unit').val(), $('#consignee_building_name').val()].join(" "), $('#consignee_postal_code').val(), CURRENT_COUNTRY].join(', '), "</strong></p>");

						$('.order-details .card-body').append(orderDetailsObj);
						$('.item-details .card-body').append(itemDetailsObj);
						$('.sender-details .card-body').append(senderDetailsObj);
						$('.recipient-details .card-body').append(recipientDetailsObj);
					}

					// The first for loop is for increasing the steps,
					// the second is for turning them off when going back
					// and the third with the if statement because the last line
					// can't seem to turn off when I press the first item. ¯\_(ツ)_/¯

					for (var i = 0; i < index; i++) {
						line[i].classList.add('crossed');

						for (var j = index; j < numberOfSteps; j++) {
							line[j].classList.remove('crossed');
						}
					}
					if (event.detail.to == 0) {
						for (var k = index; k < numberOfSteps; k++) {
							line[k].classList.remove('crossed');
						}
						line[0].classList.remove('crossed');
					}
				});
			}
		}

		if (typeof horizontalWizard !== undefined && horizontalWizard !== null) {
			var numberedStepper = new Stepper(horizontalWizard),
				$form = $(horizontalWizard).find('form');
			$form.each(function () {
				var $this = $(this);
				$this.validate({
					rules: {
						'order_reference': {
							required: true,
						},
						'weight': {
							required: true
						},
						'weight_unit': {
							required: true
						},
						'pickup-time-slot': {
							required: true
						},
						'pickup-date': {
							required: true
						},
						'contact_name': {
							required: true
						},
						'contact_phone': {
							required: true,
							maxlength: 10
						},
						'street_name': {
							required: true
						},
						'postal_code': {
							required: true,
							maxlength: 6
						},
						'unit': {
							required: true,
						},
						'building_name': {
							required: true,
						},
						'consignee_name': {
							required: true,
						},
						'consignee_phone': {
							required: true,
							maxlength: 10
						},
						'consignee_street_name': {
							required: true,
						},
						'consignee_unit': {
							required: true,
						},
						'consignee_building_name': {
							required: true,
						},
						'consignee_postal_code': {
							required: true,
						},
					}
				});
			});

			$(horizontalWizard)
				.find('.btn-next')
				.each(function () {
					$(this).on('click', function (e) {

						var isValid = $(this).parent().siblings('form').valid();
						if (isValid) {
							numberedStepper.next();
						} else {
							e.preventDefault();
						}
					});
				});

			$(horizontalWizard)
				.find('.btn-prev')
				.on('click', function () {
					numberedStepper.previous();
				});

			$(horizontalWizard)
				.find('.btn-submit')
				.on('click', function (e) {
					e.preventDefault();

					var $button = $(this);
					$('#return-order-error').hide();
					handleLoading($button, 'loading');

					var serviceType = $('#service_type').val();
					var orderReference = $('#order_reference').val();
					var length = $('#length').val();
					var width = $('#width').val();
					var height = $('#height').val();
					var weight = $('#weight').val();
					var weightUnit = $('#weight_unit').val();
					var description = $('#description').val();
					var date = $('#pickup-date').val();
					var timeSlot = $('#pickup-time-slot').val();
					var from = timeSlot.split('-')[0].trim();
					var to = timeSlot.split('-')[1].trim();

					var timeWindow = {
						from: from,
						to: to
					};
					var pickupNote = $('#pickup-note').val();
					var deliveryNote = $('#delivery-note').val();
					var deliveryDate = $('#delivery_date').val();

					var contactName = $('#contact_name').val();
					var contactPhone = $('#contact_phone').val();
					var contactEmail = $('#contact_email').val();
					//var contactCountry = $('#contact_country').val();
					//var contactCity = $('#contact_city').val();
					var streetName = $('#street_name').val();
					var unit = $('#unit').val();
					var buildingName = $('#step-3#building_name').val();
					var postCode = $('#postal_code').val();
					var pickup_address = [streetName, [unit, buildingName].join(' '), postCode].join(', ');

					var consigneeName = $('#consignee_name').val();
					var consigneePhone = $('#consignee_phone').val();
					var consigneeEmail = $('#consignee_email').val();
					//var consigneeCountry = $('#consignee_country').val();
					//var consigneeCity = $('#consignee_city').val();
					var consigneeStreetName = $('#consignee_street_name').val();
					var consigneeUnit = $('#consignee_unit').val();
					var consigneeBuildingName = $('#consignee_building_name').val();
					var consigneePostCode = $('#consignee_postal_code').val();
					var consignee_address = [consigneeStreetName, [consigneeUnit, consigneeBuildingName].join(" "), consigneePostCode].join(',');

					var data = {
						ref: orderReference,
						service_code: serviceType,
						pickup_details: {
							contact_name: contactName,
							phone_number: contactPhone,
							email: contactEmail,
							address: pickup_address,
							postcode: postCode,
							country_code: 'SG',
							date: date,
						},
						consignee_details: {
							contact_name: consigneeName,
							phone_number: consigneePhone,
							email: consigneeEmail,
							address: consignee_address,
							postcode: consigneePostCode,
							country_code: 'SG',
							date: date,
						},
						timeWindow: timeWindow,
						item_details: {
							length: length,
							width: width,
							height: height,
							weight: weight,
							weight_unit: weightUnit,
							description: description,
						}
					};

					$.ajax({
						type: "POST",
						url: 'admin.php?page=jt-woocommerce&tab=orders&action=create_return_order',
						data: JSON.stringify(data),
						contentType: 'application/json',
						dataType: 'text'
					}).done(function (res) {
						handleLoading($button);
						var nextUrl = window.location.href.replace('create-return-order', 'return-orders');
						window.location.href = nextUrl;
					}).fail(function (err) {
						handleLoading($button);
						var sign = 'ERROR:';
						var errorIndex = err.responseText.lastIndexOf(sign);
						var error = err.responseText.substring(errorIndex + sign.length);
						var message = JSON.parse(error).data.message;
						$('#return-order-error span').html(message);
						$('#return-order-error').show();
					});
				});
		}
	});
})(jQuery);
