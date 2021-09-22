(function($) {
  $(function () {
    $('input.input-shipping-method').findPickupPoints();
  });
})(jQuery);
(function ( $ ) {
  'use strict';

  $.fn.extend({
    findPickupPoints: function () {
      return this.each(function() {
        let $element = $(this);
        let $container = $(this).closest('.item');
        let url = $element.data('pickup-point-provider-url');
        let csrfToken = $element.data('csrf-token');


        $(this).on('change', function(event) {
          $('[name="app_checkout_select_shipping_and_payment[pickup_point]"]').prop('checked', false).prop('required', false);

          $container.find('[name="app_checkout_select_shipping_and_payment[pickup_point]"]').prop('disabled', false).prop('required', false);
        });

        if(!url) {
          return;
        }

        $.ajax({
          method: 'GET',
          cache: false,
          url: url,
          beforeSend: function (settings) {
            settings.data = {
              _csrf_token: csrfToken
            };

            removePickupPoints($container);
            $container.addClass('loading');

            return settings;
          },
          success: function (response) {
            addPickupPoints($container, response);
            $container.find('[name="app_checkout_select_shipping_and_payment[pickup_point]"]').on('change', function(event) {
              $(this).parents('.shipment').find('[data-shipment-point]').val($(this).val());
            });
            $container.find('[name="app_checkout_select_shipping_and_payment[pickup_point]"]').on('click', function(event) {
              $(this).parents('.item').find('.input-shipping-method').prop('checked', true);
            });
          },
          error: function (response) {
            console.log(response);
          },
          complete: function () {
            $container.removeClass('loading');
          }
        });
      });
    }
  });

  function removePickupPoints($container) {
    $container.find('.pickup-points').remove();
  }

  /**
   *
   * @param {object} $container
   * @param {array} pickupPoints
   */
  function addPickupPoints($container, pickupPoints) {
    let template = document.querySelector('#pickup-point');

    pickupPoints.forEach(function (element) {
      let item = template.content.cloneNode(true);
      item.querySelector('[data-field="name"]').innerHTML = element.name;
      item.querySelector('[data-field="address"]').innerHTML = element.address + ', ' + element.zipCode + ', ' + element.city;
      item.querySelector('[data-field="input"]').value = element.id;
      item.querySelector('[data-field="input"]').name = 'app_checkout_select_shipping_and_payment[pickup_point]';

      $container.find('.list').append($(item));
    });



  }
})( jQuery );
