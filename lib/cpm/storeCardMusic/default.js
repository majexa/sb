window.addEvent('domready', function() {
  document.getElements('.ddItems .item').each(function(eItem) {
    var itemId = eItem.get('data-id');
    var eBuyBtn = eItem.getElement('.f_buyBtn .btn');
    eBuyBtn.addEvent('click', function(e) {
      new Event(e).preventDefault();
      new Ngn.Dialog.RequestForm({
        title: 'Оплата',
        width: 300,
        url: '/storeCardMusic/json_buy/' + itemId,
        onOkClose: function() {
          window.location = '/storeCardMusic';
        }
        //itemId: itemId
      });
    });
  });
});