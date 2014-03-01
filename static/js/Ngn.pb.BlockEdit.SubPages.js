Ngn.pb.BlockEdit.SubPages = new Class({
  Extends: Ngn.pb.BlockEdit,


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