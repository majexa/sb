Ngn.Pb.BlockEdit.Tags = new Class({
  Extends: Ngn.Pb.BlockEdit,

  init: function() {
    new Ngn.UlMenu(this.eBlock.getElement('ul'));
    if (!Ngn.isAdmin) return;
    this.replaceEditBlockBtn({
      cls: 'tag2',
      caption: 'Редактировать теги'
    }, function() {
      new Ngn.DdTags.Dialog.Flat({
        blockId: this.id,
        basePath: '/sbc/ddTags',
        data: JSON.decode(this.eBlock.getElement('.bcont').getElement('.data').get('html'))
      });
    }.bind(this));
  }

});