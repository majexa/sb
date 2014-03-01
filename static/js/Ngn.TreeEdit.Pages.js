Ngn.TreeEdit.Pages = new Class({
  Extends: Ngn.TreeEdit,
  
  options: {
    id: 'tePages',
    actionUrl: '/admin/pages',
    activeIfSelected: ['delete', 'openPage']
  },
  
  getActiveButtonNames: function() {
    return this.parent().extend(['openPage']);
  },
  
  getMifTreeOptions: function() {
    var options = this.parent();
    if (!this.options.disableDragging) {
      options.initialize = function() {
        new Mif.Tree.Drag(this);
      };
    } else {
      options.initialize = function() {};
    }
    options.types.page.dropDenied = ['inside'];
    return options;
  },
  
  bindButtons: function() {
    this.parent();
    this.createButton('openPage', this.eButtons.getElement('a[class~=link]'), function() {
      window.open(this.tree.selected.data.path);
    }.bind(this));
    this.createButton('reload', this.eButtons.getElement('a[class~=refrash]'), function() {
      this.reload();
    }.bind(this));
  },

  toggleButtons: function() {
    this.parent();
    this.tree.addEvent('selectChange', function(node) {
      this.toggleButton('add', node.data.folder == 1);
      this.toggleButton('openPage', this.tree.selected ? true : false);
      this.toggleButton('reload', true);
    }.bind(this));
  }
  
});