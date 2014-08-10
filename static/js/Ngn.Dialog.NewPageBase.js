Ngn.Dialog.NewPageBase = new Class({
  Extends: Ngn.Dialog.RequestForm,

  options: {
    controllerPath: '/admin/pages',
    width: 300
  },

  getOpt: function() {
    return {
      getFormData: function() {
        var data = Ngn.Frm.toObj(this.form.eForm);
        data.parentId = this.options.pageId;
        return data;
      }
    };
  }

});
