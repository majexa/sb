Ngn.PageModule.Store = new Class({
  Implements: [Options],

  options: {
    cartBlockOptions: {}
  },

  initialize: function(options) {
    this.setOptions(options);
    window.addEvent('domready', function() {
      this.init.delay(100, this);
    }.bind(this));
  },

  init: function() {
    if (!Ngn.Cart.block) {
      /*
       Ngn.Cart.initBlock(new Element('div', {
       'class': 'cartBlock',
       styles: {
       border: '1px solid #f00',
       width: '100px',
       height: '50px'
       }
       }).inject(document.getElement('body')));
       */
      // cart container creates in hook
      if ($('cart')) Ngn.Cart.initBlock($('cart'), this.options.cartBlockOptions);
      else throw new Error('cart container does not exists');
    }
    document.getElements('.ddItems .item').each(function(eItem) {
      var itemId = eItem.get('data-id');
      var eBody = eItem.getElement('.itemBody');
      var eBtn = eBody.getElement('.f_buyBtn .btn');
      var orderParams = false;
      var added = {};
      Ngn.Cart.block.eProduct = eBody.getElement('.f_image img');
      eBtn.addEvent('click', function(e) {
        e.preventDefault();
        var cartId = itemId;
        if (orderParams) { // Дополнительные параметры заказа
          for (var i = 0; i < orderParams.length; i++) {
            cartId += '-' + (Ngn.Frm.getValueByName(orderParams[i]) || 0);
          }
        }
        /*
         if (!added[cartId]) {
         added[cartId] = parseInt(eCnt.get('value'));
         } else {
         added[cartId] += parseInt(eCnt.get('value'));
         }
         */
        added[cartId] = 1;
        Ngn.Cart.block.addItem(Ngn.Sb.page.id, cartId, added[cartId]);
        eAdded.set('html', '<a href="/' + Ngn.Cart.block.options.storeOrderController + '">Добавлено ' + added[cartId] + ' шт.</a>');
      });
      Ngn.settings('store/orderParams', function(v) {
        orderParams = v;
        eBtn.setStyle('visibility', 'visible');
      });
      /*
       var eCnt = new Element('input', {
       value: 1,
       'class': 'fld',
       maxlength: 3
       }).inject(eBody);
       new Ngn.NumberSelect(eCnt);
       */
      var eAdded = new Element('div', {'class': 'added gray'}).inject(eBody, 'after');
    });
  }

});