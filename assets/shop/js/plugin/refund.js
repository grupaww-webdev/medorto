$(document).ready(function() {
  $('[name="refund-code"]').on('change', function(event) {
    $('input[type="radio"][value="refund"]').prop('checked', true);
  });

  $('input[type="radio"][value="refund"]').on('change', function(event) {
    $('[name="refund-code"]').prop('required', true);
  });

  $('input[type="radio"][value="regular"]').on('change', function(event) {
    if (true === $(this).prop('checked')) {
      $('[name="refund-code"]').prop('checked', false).prop('required', false);
    }
  });
})
