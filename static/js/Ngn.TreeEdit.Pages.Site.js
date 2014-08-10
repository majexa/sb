Ngn.TreeEdit.Pages.Site = new Class({
  Extends: Ngn.TreeEdit.Pages,

  add: function() {
    new Ngn.Dialog.NewModulePageSimple({
      pageId: this.tree.selected.data.id,
      onSubmitSuccess: function() {
        this.reload();
        this.fireEvent('update');
      }.bind(this)
    });
  },

  bindButtons: function() {
    this.parent();
    this.createButton('editProp', this.eButtons.getElement('a[class~=editProp]'), function() {
      new Ngn.Dialog.RequestForm({
        width: 300,
        title: 'Редактирование параметров раздела',
        url: '/admin/pages/' + this.tree.selected.data.id + '/json_editPagePropSimple',
        onSubmitSuccess: function() {
          this.reload();
          this.fireEvent('update');
        }.bind(this)
      });
    }.bind(this));
  },

  toggleButtons: function() {
    this.parent();
    this.tree.addEvent('selectChange', function(node) {
      this.toggleButton('editProp', this.tree.selected ? true : false);
    }.bind(this));
  }

});
