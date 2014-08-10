Ngn.Frm.Page = new Class({
  Implements: [Options],

  options: {
    btnPlaceholderClass: 'pageLinkPlaceholder',
    btnClass: 'pageLink',
    actionUrl: '/default/pageTree',
    dropdownOpt: {
      width: 500
    },
    dd: false
  },

  toggleDisabled: function(flag) {
    this.dropdown.disabled = !flag;
  },

  initialize: function(eRow, options) {
    this.setOptions(options);
    this.eInput = eRow.getElement('input');
    this.initVirtualElement(this.eInput);
    this.ePlaceholder = eRow.getElement(this.options.btnPlaceholderClass);
    if (!this.ePlaceholder) {
      var d = Elements.from('<div class="' + this.options.btnPlaceholderClass + '"><a href="" class="iconBtn ' + this.options.btnClass + '"><i></i></a></div><div class="clear"></div>');
      d.each(function(el) {
        el.inject(eRow, 'bottom');
      });
      this.ePlaceholder = d[0];
    }
    var inputHightlightFx = new Fx.Tween(this.eInput, { duration: 500 });
    var obj = this;
    this.dropdown = new Ngn.DropdownWin(this.ePlaceholder, $merge(this.options.dropdownOpt, {
      winClass: 'dropdownWin-pagesTree',
      eParent: this.ePlaceholder.getParent('.dialog'),
      onDomReady: function() {
        this.te = new Ngn.TreeEdit(this.eBody, {
          id: 'pageSelect',
          actionUrl: obj.options.actionUrl,
          enableStorage: false,
          disableDragging: true,
          mifTreeOptions: {
            isDisabledSelect: function(node) {
              return !node.data.dd;
            }
          },
          onDataLoad: function(data) {
            (function() {
              this.eBody.getFirst().setStyle('height', this.bodyInitHeight);
              this.te.tree.addEvent('userSelect', function(node) {
                // Скрываем DropdownWin
                this.startHide();
              }.bind(this));
              this.te.tree.addEvent('select', function(node) {
                if (obj.select(node))
                  inputHightlightFx.start('background-color', '#FFEB8F', '#FFFFFF');
              }.bind(this));
              this.te.openFirstRoot();
            }).delay(100, this);
          }.bind(this)
        }).init();
      }
    }));
  },

  /**
   * @param Ngn.Tree.Node
   * @returns {Boolean}
   */
  select: function(node) {
  }

});

Ngn.Frm.Page.implement(Ngn.Frm.virtualElement);

// -- check --