Ngn.Dialog.NewModulePage = new Class({
  Extends: Ngn.Dialog.NewPageBase,

  actionName: 'newModulePage',

  initialize: function(_opts) {
    this.parent($merge(_opts, this.getOpt(), {
      title: 'Создание нового раздела',
      url: this.options.controllerPath + '/' + _opts.pageId + '/json_' + this.actionName
    }));
  },

  formResponse: function(r) {
    this.parent(r);
    this.message.getElement('input[name=title]').focus();
    this.afterFormResponse();
  },

  afterFormResponse: function() {
    Ngn.frm.initTranslateField('titlei', 'namei');
    Ngn.frm.initCopySelectValue('modulei', 'namei');
    Ngn.frm.initCopySelectTitle('modulei', 'titlei');
  }

});
