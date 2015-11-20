Ngn.Cart = {};

Ngn.Cart.OrderList = new Class({

  initialize: function(eProducts, btnClean) {
    this.eTotalV = eProducts.getElements('tr.total .priceV');
    this.eProducts = eProducts;
    btnClean.addEvent('click', function(e) {
      e.preventDefault();
      if (!Ngn.confirm()) return;
      if (Ngn.Cart.block) Ngn.Cart.block.clear(true);
    });
    this.eProducts.getElement('tbody').getElements('tr').each(function(eTr) {
      var pageId = eTr.get('data-pageId');
      var cartId = eTr.get('data-cartId');
      var eBtn = eTr.getElement('.delete');
      eBtn.addEvent('click', function(e){
        e.preventDefault();
        if (!Ngn.confirm()) return;
        if (Ngn.Cart.block) Ngn.Cart.block.removeItem(pageId, cartId, true);
      });
      var eCnt = eTr.getElement('.cntV');
      var cnt = parseInt(eCnt.get('html'));
      new Ngn.NumberSelect(
        new Element('input', {
          value: cnt,
          'class': 'fld',
          maxlength: 3
        }).replaces(eCnt), {
          onChange: function(n) {
            this.refrashTotal();
            if (Ngn.Cart.block) Ngn.Cart.block.updateCnt(pageId, cartId, n);
          }.bind(this)
        }
      );
    }.bind(this));
    this.refrashTotal();
  },

  refrashTotal: function() {
    var total = 0;
    this.eProducts.getElement('tbody').getElements('tr').each(function(eTr) {
      total += eTr.getElement('.fld').get('value') * eTr.getElement('.priceV').get('html');
    });
    this.eTotalV.set('html', total);
  }

});

Ngn.Cart.utils = {

  initData: function(callback) {
    new Ngn.Request.JSON({
      url: '/default/storeCart/json_getIds',
      onComplete: function(d) {
        callback(d);
      }
    }).send();
  },

  remove: function(pageId, cartId, callback) {
    new Ngn.Request({
      url: '/default/storeCart/ajax_delete',
      onComplete: function() {
        callback();
      }
    }).get({
        pageId: pageId,
        cartId: cartId
      });
  },

  add: function(pageId, cartId, cnt) {
    new Request({
      url: '/default/storeCart/ajax_add'
    }).get({
        pageId: pageId,
        cartId: cartId,
        cnt: cnt
      });
  },

  updateCnt: function(pageId, cartId, cnt) {
    new Request({
      url: '/default/storeCart/ajax_updateCnt'
    }).get({
        pageId: pageId,
        cartId: cartId,
        cnt: cnt
      });
  },

  clear: function(callback) {
    new Request({
      url: '/default/storeCart/ajax_clear',
      onComplete: function() {
        callback();
      }
    }).send();
  }

};

Ngn.Cart.Block = new Class({
  Implements: [Options],

  options: {
    storeOrderController: 'storeOrder'
  },

  eProduct: null,

  initialize: function(eCart, options) {
    this.setOptions(options);
    this.eCart = eCart;
    this.eCart.set('html', '');
    this.eCaption = new Element('a', {
      href: '/' + this.options.storeOrderController,
      styles: {
        'display': 'none'
      }
    }).inject(this.eCart);
    this.eLabel = new Element('span', {
      html: 'Корзина: '
    }).inject(this.eCaption);
    this.eCount = new Element('span').inject(this.eLabel, 'after');
    this.initData();
  },

  count: 0,

  data: {},

  initData:  function() {
    this.restoreData();
    this.initCount();
    this.refrashElement();
    Ngn.Cart.utils.initData(function(d) {
      if (d.length == 0) {
        this.data = {};
      } else {
        for (var i=0; i<d.length; i++) {
          d[i].cnt = parseInt(d[i].cnt);
          if (!this.data[d[i].pageId]) this.data[d[i].pageId] = {};
          this.data[d[i].pageId][d[i].cartId] = d[i].cnt;
          this.count += d[i].cnt;
        }
      }
      this.storeData();
      this.initCount();
      this.refrashElement();
    }.bind(this));
  },

  initCount: function() {
    var count = 0;
    for (var i in this.data)
      for (var j in this.data[i])
        count += this.data[i][j];
    this.count = count;
  },

  removeItem: function(pageId, cartId, reload) {
    delete this.data[pageId][cartId];
    Ngn.Cart.utils.remove(pageId, cartId, function() {
      if (reload) window.location.reload(true);
    });
    this.update();
  },

  clear: function(reload) {
    this.data = {};
    Ngn.Cart.utils.clear(function() {
      if (reload) window.location.reload(true);
    });
    this.update();
  },

  update: function() {
    this.storeData();
    this.initCount();
    this.refrashElement();
  },

  storeData: function() {
    Ngn.Storage.json.set('cart', this.data);
  },

  restoreData: function() {
    this.data = Ngn.Storage.json.get('cart') || {};
  },

  addItem: function(pageId, cartId, cnt) {
    cnt = cnt || 1;
    if (!this.data[pageId]) this.data[pageId] = {};
    // this.data[pageId][cartId] = this.data[pageId][cartId] ? this.data[pageId][cartId]+1 : 1;
    this.data[pageId][cartId] = cnt;
    Ngn.Cart.utils.add(pageId, cartId, cnt);
    this.update();
    this.showMove();
  },

  refrashElement: function() {
    this.eCaption.setStyle('display', this.count ? 'block' : 'none');
    this.eCount.set('html', this.count);
  },

  updateCnt: function(pageId, cartId, cnt) {
    Ngn.Cart.utils.updateCnt(pageId, cartId, cnt);
    this.data[pageId][cartId] = cnt;
    this.update();
  },

  showMove: function() {
    var el = this.eProduct;
    var eClone = el.clone().inject(document.getElement('body'), 'bottom').setStyle('position', 'absolute');
    var pos = el.getPosition();
    eClone.setPosition(pos);
    var size = eClone.getSize();
    var pos2 = this.eCart.getPosition();
    new Fx.Morph(eClone).start({
      'top': [pos.y, pos2.y],
      'left': [pos.x, pos2.x],
      'width': [size.x+'px', 50+'px'],
      'height': [size.y+'px', 15+'px'],
      'opacity': [1, 0]
    });
  }

});

Ngn.Cart.initBlock = function(el, options) {
  Ngn.Cart.block = new Ngn.Cart.Block(el, options);
};
