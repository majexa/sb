Ngn.Dialog.TreeEdit.Pages = new Class({
  Extends: Ngn.Dialog.TreeEdit,
  
  options: {
    id: 'editPages',
    title: 'Редактирование разделов',
    footer: false,
    actionUrl: '/sbc/pages'
  },
  
  buildMessage: function() {
    return Elements.from('\
<div>\
  <div class="treeMenu iconsSet">\
    <small>\
      <a href="#" class="add dgray"><i></i>Создать</a>\
      <a href="#" class="link dgray"><i></i>Открыть</a>\
      <a href="#" class="editProp dgray"><i></i>Параметры</a>\
      <a href="#" class="delete dgray"><i></i>Удалить</a>\
    </small>\
    <div class="clear"><!-- --></div>\
  </div>\
  <div class="treeContainer"></div></div>\
</div>\
    ')[0];
  },
  
  init: function() {
    this.eTreeContainer = this.message.getElement('.treeContainer');
    this.eTreeMenu = this.message.getElement('.treeMenu');
    var tree = new Ngn.TreeEdit.Pages.Site(
      this.eTreeContainer,
      {
        actionUrl: this.options.actionUrl,
        buttons: this.eTreeMenu,
        onUpdate: function() {
          //this.updateBlock();
        }.bind(this)
      }
    ).init();
    tree.addEvent('dataLoad', function() {
      c('1111111111');
      this.eTreeContainer.setStyle('height',
        (this.message.getSize().y - this.eTreeMenu.getSize().y) + 'px');
      this.initReduceHeight(true);
      tree.toggleAll(true);
    }.bind(this));
  }
  
});