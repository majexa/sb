Ngn.Pb.BlockEdit.SubPages = new Class({
  Extends: Ngn.Pb.BlockEdit,

  init: function() {
    if (!Ngn.isAdmin) return;
    this.addEditBlockBtn({
      cls: 'editPage',
      caption: 'Редактировать разделы'
    }, function() {
      new Ngn.Dialog.TreeEdit.Pages({
        blockId: this.id
      });
    });
  }

});