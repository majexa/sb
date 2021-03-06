Ngn.Dialog.NewPage = new Class({
  Extends: Ngn.Dialog.NewPageBase,

  initialize: function(_opts) {
    this.parent(Object.merge(_opts, this.getOpt(), {
      title: 'Ручное создание нового раздела',
      url: this.options.controllerPath + '/' + _opts.pageId + '/json_newPage'
    }));
  },

  urlResponse: function(r) {
    this.parent(r);
    Ngn.Frm.initTranslateField('titlei', 'namei');
    this.message.getElement('input[name=title]').focus();
  }
  
});

