$(document).ready(function() {
  const shippingAddressCheckbox = $('#sylius_checkout_address_differentShippingAddress');
  const shippingAddressContainer = $('#sylius-shipping-address');
  const toggleShippingAddress = function toggleShippingAddress() {
    shippingAddressContainer.toggle(shippingAddressCheckbox.prop('checked'));
  };
  toggleShippingAddress();
  shippingAddressCheckbox.on('change', toggleShippingAddress);
});
