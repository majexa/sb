Ngn.Pb.BlockEdit.Tags = new Class({
  Extends: Ngn.Pb.BlockEdit,

  init: function() {
    if (!Ngn.isAdmin) return;
    this.replaceEditBlockBtn({
      cls: 'tag2',
      caption: 'Редактировать рубрики'
    }, function() {
      new Ngn.DdTags.Dialog.Flat({
        blockId: this.id,
        basePath: '/sbc/ddTags',
        data: JSON.decode(this.eBlock.getElement('.bcont').getElement('.data').get('html'))
      });
    }.bind(this));
    new Ngn.UlMenu(this.eBlock.getElement('ul'));
  }

});