Ngn.Frm.Page.Id = new Class({
  Extends: Ngn.Frm.Page,

  options: {
    btnClass: 'pageId',
    dropdownOpt: {
      width: 250,
      height: 150
    }
  },

  initialize: function(eRow, options) {
    this.parent(eRow, options);
    this.eTitle = new Element('div', {
      'class': 'pageIdTitle'
    }).inject(this.ePlaceholder, 'after');
    var defaultPageId = this.eInput.get('value');
    if (defaultPageId) {
      new Ngn.Request.JSON({
        url: this.options.actionUrl + '/json_getNode/' + defaultPageId,
        onComplete: function(r) {
          this.setTitle(r);
        }.bind(this)
      }).send();
    }
  },

  setTitle: function(data) {
    this.eTitle.set('html', data.title + ' <small class="gray">(' + data.path + ')</small>');
  },

  select: function(node) {
    this.setTitle(node.data);
    this.eInput.set('value', node.data.id);
    return true;
  }

});