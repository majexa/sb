Ngn.pb.BlockEdit.Tags = new Class({
  Extends: Ngn.pb.BlockEdit,

  init: function() {
    if (!Ngn.isAdmin) return;
    this.replaceEditBlockBtn({
      cls: 'tag2',
      caption: 'Редактировать рубрики'
    }, function() {
      new Ngn.DdTags.Dialog.Tree({
        blockId: this.id,
        data: JSON.decode(this.eBlock.getElement('.bcont').getElement('.data').get('html'))
      });
    }.bind(this));
    //if (pageBlock.settings.hideSubLevels) ->
    new Ngn.UlMenu(this.eBlock.getElement('ul'));
  }

});