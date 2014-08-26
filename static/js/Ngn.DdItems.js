Ngn.DdItemEdit = new Class({

  initialize: function(eItem) {

  }

});
Ngn.DdItemsEdit = new Class({

  initialize: function(eItems) {
    eItems.getElement('.item').each(function(eItem) {
      eItem.get('data-id');
    });
  }

});