
// import $ from 'jquery';

const handleProductOptionsChange = function handleProductOptionsChange() {
  $('[name*="sylius_add_to_cart[cartItem][variant]"]').on('change', () => {
    let selector = '';

    $('#sylius-product-adding-to-cart select[data-option]').each((index, element) => {
      const select = $(element);
      const option = select.find('option:selected').val();
      selector += `[data-${select.attr('data-option')}="${option}"]`;
    });

    const price = $('#sylius-variants-pricing').find(selector).attr('data-value');
    const originalPrice = $('#sylius-variants-pricing').find(selector).attr('data-original-price');
    const minimumPrice = $('#sylius-variants-pricing').find(selector).attr('data-minimum-price');


    if (price !== undefined) {
      $('#product-price').text(price);
      $('button[type=submit]').removeAttr('disabled');

      if (originalPrice !== undefined) {
        $('#product-original-price').css('display', 'inline').html(`<del>${originalPrice}</del>`);
      } else {
        $('#product-original-price').css('display', 'none');
      }
      if (minimumPrice !== undefined && originalPrice !== undefined) {
        $('#product-minimum-price-banner').css('display', 'inline');
        $('#product-minimum-price-value').css('display', 'inline').html(`${minimumPrice}`);
      } else {
        $('#product-minimum-price-banner').css('display', 'none');
        $('#product-minimum-price-value').css('display', 'none');
      }
    } else {
      $('#product-price').text($('#sylius-variants-pricing').attr('data-unavailable-text'));
      $('button[type=submit]').attr('disabled', 'disabled');
    }
  });
};

const handleProductVariantsChange = function handleProductVariantsChange() {
  $('[name="sylius_add_to_cart[cartItem][variant]"]').on('change', (event) => {
    const priceRow = $(event.currentTarget).parents('tr').find('.sylius-product-variant-price');
    const price = priceRow.text();
    const originalPrice = priceRow.attr('data-original-price');
    $('#product-price').text(price);

    console.log('test 2');

    if (originalPrice !== undefined) {
      $('#product-original-price').css('display', 'inline').html(`<del>${originalPrice}</del>`);
    } else {
      $('#product-original-price').css('display', 'none');
    }
  });
};

$( document ).ready(function() {

  console.log( "ready!" );

  if ($('#sylius-variants-pricing').length > 0) {
    handleProductOptionsChange();
  } else if ($('#sylius-product-variants').length > 0) {
    handleProductVariantsChange();
  }
});

$.fn.extend({
  variantPrices() {
    if ($('#sylius-variants-pricing').length > 0) {
      handleProductOptionsChange();
    } else if ($('#sylius-product-variants').length > 0) {
      handleProductVariantsChange();
    }
  },
});
