window.addEvent('domready', function() {
  var btnShowHiddenPrices = $('btnShowHiddenPrices');
  if (btnShowHiddenPrices) btnShowHiddenPrices.addEvent('click', function(e) {
    e.preventDefault();
    new Ngn.Dialog.Confirm({
      message: 'Для того, что бы узнать оптовые цены вам необходимо зарегистрироваться',
      onOkClose: function() {
        var dialog = new Ngn.Dialog.Auth();
        dialog.addEvent('activation', function() {
      	  Ngn.Dialog.openWhenClosed(dialog, Ngn.Dialog.Msg, {
            message: 'Вы отправили заявку на регистрацию. Скоро с вами свяжется менеджер'
          });
        });
      }
    });
  });
});

Ngn.cart.setPrices = function(name) {
  var tagId = Ngn.Frm.getValueByName(name);
  Ngn.cart.setPrice(name, 0, tagId);
  Ngn.cart.setPrice(name, 1, tagId);
};
Ngn.cart.setPrice = function(name, n, tagId) {
  var price = Ngn.cart.prices[name][n][tagId];
  if (!price) return;
  var ePrice = document.getElement('.f_price'+(n ? n+1 : '')+' .v');
  if (ePrice) ePrice.set('html', price + ' руб.');
};
