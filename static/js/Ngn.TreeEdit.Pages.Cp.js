Ngn.TreeEdit.Pages.Cp = new Class({
  Extends: Ngn.TreeEdit.Pages,

  initialize: function(twoScreens, container, options) {
    this.twoScreens = twoScreens;
    this.parent(container, options);
  },

  add: function() {
    this.twoScreens.openModuleCreateDialog(this.tree.selected ? this.tree.selected.data.id : 0);
  },

  createPage: function() {
    this.twoScreens.openCreateDialog(this.tree.selected.data.id);
  },

  bindButtons: function() {
    this.parent();
    this.createButton('editContent', this.eButtons.getElement('a[class~=edit]'), function() {
      window.location = Ngn.Url.getPath(1) + '/pages/' + this.tree.selected.data.id + '/editContent';
    }.bind(this));
    this.createButton('openFolder', this.eButtons.getElement('a[class~=openFolder]'), function() {
      window.location = Ngn.Url.getPath(1) + '/pages/' + this.tree.selected.data.id;
    }.bind(this));
    this.createButton('createPage', this.eButtons.getElement('a[class~=add2]'), this.createPage.bind(this));
    this.createButton('editProp', this.eButtons.getElement('a[class~=editProp]'), function() {
      this.twoScreens.openPagePropDialog(this.tree.selected.data.id)
    }.bind(this));
    this.createButton('controllerSettings', this.eButtons.getElement('a[class~=controllerSettings]'), function() {
      this.twoScreens.openControllerSettingsDialog(this.tree.selected.data.id);
    }.bind(this));
    this.createButton('slaveControllerSettings', this.eButtons.getElement('a[class~=slaveControllerSettings]'), function() {
      this.twoScreens.openControllerSettingsDialog(this.tree.selected.data.slavePageId);
    }.bind(this));
    this.createButton('pageBlocks', this.eButtons.getElement('a[class~=pageBlocks]'), function() {
      window.location = Ngn.Url.getPath(1) + '/pageBlocks/' + this.tree.selected.data.id;
    }.bind(this));
    this.createButton('layout', this.eButtons.getElement('a[class~=layout]'), function() {
      window.location = Ngn.Url.getPath(1) + '/pageLayout/' + this.tree.selected.data.id;
    }.bind(this));
    this.createButton('ddo', this.eButtons.getElement('a[class~=ddo]'), function() {
      window.location = Ngn.Url.getPath(1) + '/ddo/' + this.tree.selected.data.id;
    }.bind(this));
    this.createButton('meta', this.eButtons.getElement('a[class~=meta]'), function() {
      new Ngn.Dialog.RequestForm({
        url: Ngn.Url.getPath(1) + '/pageMeta/' + this.tree.selected.data.id
      });
    }.bind(this));
    this.createButton('privileges', this.eButtons.getElement('a[class~=privileges]'), function() {
      new Ngn.Dialog.RequestForm({
        url: Ngn.Url.getPath(1) + '/pagePriv/' + this.tree.selected.data.id + '/json_editPage'
      });
    }.bind(this));
    this.createButton('onMap', this.eButtons.getElement('a[class~=onMap]'), function() {
      new Ngn.Request({
        url: Ngn.Url.getPath(1) + '/pages/' + this.tree.selected.data.id + '?a=ajax_onMenu&onMenu=' + (this.tree.selected.data.onMap ? 0 : 1),
        onComplete: function() {
          this.tree.reload();
          this.reloadItemsList();
        }.bind(this)
      }).get();
    }.bind(this));
  },

  toggleButtons: function() {
    this.parent();
    this.tree.addEvent('selectChange', function(node) {
      this.toggleButton('createPage', node.data.folder == 1);
      this.toggleButton('openFolder', node.data.folder == 1);
      this.toggleButton('editContent', node.data.editableContent);
      this.toggleButton('controllerSettings', true);
      this.toggleButton('slaveControllerSettings', node.data.isMaster);
      this.toggleButton('editProp', true);
      this.toggleButton('meta', true);
      this.toggleButton('pageBlocks', true);
      this.toggleButton('layout', true);
      this.toggleButton('privileges', node.data.ddItems); // пока что разрешаем ф-ю только для контроллера записей
      this.toggleButton('onMap', true);
      if (this.buttons.onMap) {
        if (node.data.onMap) {
          this.buttons.onMap.el.removeClass('sitemap').addClass('sitemapRed');
        } else {
          this.buttons.onMap.el.removeClass('sitemapRed').addClass('sitemap');
        }
      }
      this.toggleButton('ddo', node.data.dd);
    }.bind(this));
  },

  openModuleCreateDialog: function(pageId) {
    new Ngn.Dialog.NewModulePage({
      pageId: pageId,
      onSubmitSuccess: function() {
        this.tree.reload();
        //this.reloadItemsList();
      }.bind(this)
    });
  },

  openCreateDialog: function(pageId) {
    new Ngn.Dialog.NewPage({
      pageId: pageId,
      onSubmitSuccess: function() {
        this.tree.reload();
        this.reloadItemsList();
      }.bind(this)
    });
  },

  openPagePropDialog: function(pageId) {
    new Ngn.Dialog.RequestForm({
      title: 'Редактирование параметров раздела',
      url: Ngn.Url.getPath(2) + '/' + pageId + '/json_editPageProp',
      onSubmitSuccess: function() {
        this.tree.reload();
        this.reloadItemsList();
      }.bind(this)
    });
  },

  openControllerSettingsDialog: function(pageId) {
    new Ngn.Dialog.Queue.Request({
      url: Ngn.Url.getPath(2) + '/' + pageId + '?a=json_editControllerSettings',
      width: 600,
      reduceHeight: true
    });
  },

  openNewDdItemDialog: function(eBtn) {
    new Ngn.Dialog.RequestForm({
      title: false,
      url: eBtn.get('href').replace('new', 'json_new'),
      onSubmitSuccess: function() {
        window.location.reload(true);
      }
    });
  }

});
