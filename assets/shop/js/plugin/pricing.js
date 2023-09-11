
// import $ from 'jquery';

const handleProductAvailable = function handleProductOptionsChange(selector) {
  let pricing = $('#sylius-variants-pricing');

  const quantity = pricing.find(selector).attr('data-quantity');
  const trackable = pricing.find(selector).attr('data-trackable');

  console.log({
    'quantity': quantity,
    'trackable': trackable
  });

  if (trackable)
  {
    $('#product-quantity-section').css('display', 'inline');
    if (quantity > 0) {
      $('#product-quantity-value').text(quantity);
    } else {
      $('#product-quantity-value').text($('#sylius-variants-pricing').attr('data-unavailable-text'));
    }
  }
  else
  {
    $('#product-quantity-section').css('display', 'none');
  }
}

function calculateOptionPrices()
{
  let selector = '';
  let pricing = $('#sylius-variants-pricing');

  $('#sylius-product-adding-to-cart select[data-option]').each((index, element) => {
    const select = $(element);
    const option = select.find('option:selected').val();
    selector += `[data-${select.attr('data-option')}="${option}"]`;
  });

  const price = pricing.find(selector).attr('data-value');
  const originalPrice = pricing.find(selector).attr('data-original-price');
  const minimumPrice = pricing.find(selector).attr('data-minimum-price');

  handleProductAvailable(selector);

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


  $(".refund_value").each(function (){
    var refundSelector =  selector + `[data-refund="${$(this).data('refund')}"]`;
    const refundPrice = $('#sylius-refund-pricing').find(refundSelector).attr('data-value');
    $(this).html(refundPrice);
  });
}

const handleProductOptionsChange = function handleProductOptionsChange() {
  $('[name*="sylius_add_to_cart[cartItem][variant]"]').on('change', () => {
    let selector = '';
    let pricing = $('#sylius-variants-pricing');

    $('#sylius-product-adding-to-cart select[data-option]').each((index, element) => {
      const select = $(element);
      const option = select.find('option:selected').val();
      selector += `[data-${select.attr('data-option')}="${option}"]`;
    });

    const price = pricing.find(selector).attr('data-value');
    const originalPrice = pricing.find(selector).attr('data-original-price');
    const minimumPrice = pricing.find(selector).attr('data-minimum-price');

    handleProductAvailable(selector);


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

      $(".refund_value").each(function (){
        var refundSelector =  selector + `[data-refund="${$(this).data('refund')}"]`;
        const refundPrice = $('#sylius-refund-pricing').find(refundSelector).attr('data-value');
        $(this).html(refundPrice);
      });
      $(".refund_piece").each(function (){
        var refundSelector =  selector + `[data-refund="${$(this).data('refund')}"]`;
        const refundPrice = $('#sylius-refund-pricing').find(refundSelector).attr('data-piece');
        $(this).html(refundPrice);
      });
      $(".refund_pack").each(function (){
        var refundSelector =  selector + `[data-refund="${$(this).data('refund')}"]`;
        const refundPrice = $('#sylius-refund-pricing').find(refundSelector).attr('data-pack');
        $(this).html(refundPrice);
      });

    } else {
      $('#product-price').text($('#sylius-variants-pricing').attr('data-unavailable-text'));
      $('button[type=submit]').attr('disabled', 'disabled');

      $('#product-minimum-price-banner').css('display', 'none');
      $('#product-minimum-price-value').css('display', 'none');

      $('#product-original-price').css('display', 'none');

      $(".refund_value").each(function (){
        $(this).html($('#sylius-variants-pricing').attr('data-unavailable-text'));
      });
      $(".refund_piece").each(function (){
        $(this).html($('#sylius-variants-pricing').attr('data-unavailable-text'));
      });
      $(".refund_pack").each(function (){
        $(this).html($('#sylius-variants-pricing').attr('data-unavailable-text'));
      });
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

  if ($('#sylius-variants-pricing').length > 0) {
    calculateOptionPrices();
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
