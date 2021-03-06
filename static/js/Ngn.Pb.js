Ngn.Pb = {};

Ngn.Pb.Interface = new Class({
  Implements: [Options],

  options: {
    wrapperSelector: '.layout',
    //colSelector: '.col',
    //colBodySelector: '.blocksBody',
    handler: '.dragBox',
    controllerPath: '/sbc/pageBlocks',
    simple: true,
    disableDeleteBtn: false,
    colBodySelector: '.pageBlocks'
  },

  initialize: function(options) {
    this.setOptions(options);
    this.eWrapper = document.getElement(this.options.wrapperSelector);
    this.initCols();
    this.initBlocks();
    this.initSortables();
  },

  allowGlobalBtn: false,

  initCols: function() {
    var i = 0;
    this.cols = [];
    this.eWrapper.getElements('.col').each(function(eCol) {
      if (eCol.hasClass('ct_content')) this.allowGlobalBtn = true; // если есть колонка с контентом, глобальные блоки на других колонках разрешены
      if (!eCol.hasClass('allowBlocks')) return;
      new Ngn.Pb.Col(eCol, this);
      var eColBody = eCol.getElement(this.options.colBodySelector);
      if (!eColBody) throw new Error('col body not found by selector: ' + this.options.colBodySelector + ', col #' + (i + 1));
      this.cols[i] = eColBody;
      i++;
    }.bind(this));
    //Ngn.equalItemHeights(this.cols, true);
  },

  initSortables: function() {
    if (!this.cols.length) return;
    this.sortables = new Sortables(this.cols, {
      revert: true,
      clone: true,
      handle: this.options.handler
    });
    this.sortables.addEvent('start', function(el, clone) {
      clone.setStyle('z-index', 9999);
      clone.addClass('move');
    });
    this.sortables.addEvent('stop', function(el, clone) {
      el.removeClass('nonActive');
      clone.removeClass('move');
    });
    // Строка отвечающая за изменение порядка
    this.orderState = this.sortables.serialize().join(',');
    this.sortables.addEvent('complete', function(el, clone) {
      var cols = this.sortables.serialize(false, function(eBlock, index) {
        if (!eBlock.get('id')) return null;
        return {
          id: eBlock.get('id').replace('block_', ''),
          colN: eBlock.getParent('.col').get('id').replace('col', '')
        }
      }.bind(this));
      if (this.cols.length == 1) cols = [cols];
      if (this.orderState == this.sortables.serialize().join(',')) return;
      el.addClass('loading');
      new Request({
        url: this.options.controllerPath + '/ajax_updateBlocks',
        onComplete: function() {
          el.removeClass('loading');
          this.orderState = this.sortables.serialize().join(',');
        }.bind(this)
      }).post({
          cols: cols
        });
    }.bind(this));
  },

  blocks: {},

  initBlocks: function() {
    this.eWrapper.getElements('.block').each(function(el) {
      var typeClsName = 'Ngn.Pb.BlockEdit.' + ucfirst(Ngn.Pb.getType(el));
      c(typeClsName);
      var typeCls = eval(typeClsName);
      typeCls ? c('init ' + typeClsName) : c('init Ngn.Pb.BlockEdit');
      var cls = typeCls || Ngn.Pb.BlockEdit;
      var b = new cls(el, this);
      this.blocks[b.id] = b;
    }.bind(this));
  }

});

Ngn.Pb.positionRight = function(el, eCol, padSelector) {
  var colW = eCol.getSizeWithoutPadding().x;
  var eFirstPageBlockEdit = eCol.getElement(padSelector);
  var eb1W = eFirstPageBlockEdit ? eFirstPageBlockEdit.getSize().x : 0;
  var selfW = el.getSize().x;
  el.setStyle('left', colW - eb1W - selfW - 6 - 5);
};

Ngn.Pb.Col = new Class({

  initialize: function(eCol, interface) {
    this.interface = interface;
    this.eCol = eCol;
    this.eBtns = new Element('div', {
      'class': 'editBlock smIcons bordered blue'
    }).inject(eCol, 'top');
    if (eCol.hasClass('allowBlocks')) this.addBtnCreate();
  },

  addBtnCreate: function() {
    Ngn.Btn.btn2('Добавить блок в ' + this.eCol.get('data-n') + '-ю колонку', 'add').inject(this.eBtns).addEvent('click', function(e) {
      e.preventDefault();
      // новый вариант
      new Ngn.Dialog.RequestForm({
        url: this.interface.options.controllerPath + '/json_newBlock' + (this.interface.options.simple ? 'Simple' : '') + '/' + this.eCol.get('data-n'),
        width: 350,
        onComplete2: function() {
          window.location.reload(true);
        }
      });

      /*
       new Ngn.Dialog.Queue.Request.Form({
       url: this.interface.options.controllerPath+'/json_newBlock'+
       (this.interface.options.simple ? 'Simple' : '')+
       '/'+this.eCol.get('data-n')
       }, {
       onComplete: function() {
       window.location.reload(true);
       }
       });
       */
    }.bind(this));
  }

});

Ngn.Pb.getType = function(el) {
  return el.get('class').replace(/.* pbt_(\w+) .*/, '$1');
};

Ngn.Pb.BlockEdit = new Class({

  /**
   * @var block element
   * @var Ngn.Pb.Interface
   */
  initialize: function(eBlock, interface) {
    this.interface = interface;
    this.eBlock = eBlock;
    var id = this.eBlock.get('id');
    if (!id) return;
    this.id = this.eBlock.get('id').replace('block_', '');
    this.initEditBlock();
    this.init();
  },

  getDialogOptions: function(type) {
    if (Ngn.Pb.dialogOptions[type]) return Ngn.Pb.dialogOptions[type];
  },

  init: function() {
  },

  initEditBlock: function() {
    var el = this.eBlock;
    var type = Ngn.Pb.getType(el);
    Elements.from(Ngn.tpls.editBlock)[0].inject(el, 'top');
    el.getElement('.actv').dispose();
    this.eEditBlock = el.getElement('.editBlock');
    var eDragBox = Elements.from('<div class="dragBox"></div>')[0].inject(el, 'top');
    // Выравниваем editBlock по правому краю
    //(function() {
    //  Ngn.setToTopRight(this.eEditBlock, el, [6, 0]);
    //  Ngn.setToTopRight(eDragBox, el, [this.eEditBlock.getSize().x + 15, 0]);
    //}.bind(this)).delay(100);
    var btnEdit = el.getElement('a[class~=edit]');
    if (btnEdit) {
      btnEdit.set('href', '#');
      btnEdit.set('title', 'Редактировать блок');
      btnEdit.addEvent('click', function(e) {
        e.preventDefault();
        new Ngn.Dialog.RequestForm(Object.merge({
          url: this.interface.options.controllerPath + '/json_editBlock/' + this.id,
          width: 400,
          id: 'editBlock' + this.id,
          onSubmitSuccess: function() {
            this.reload();
          }.bind(this)
        }, this.getDialogOptions(type))).show();
      }.bind(this));
    }
    var btnDelete = el.getElement('a[class~=delete]');
    if (btnDelete) {
      // if (this.interface.options.disableDeleteBtn) {
      //   btnDelete.dispose();
      //   return;
      // }
      btnDelete.addEvent('click', function(e) {
        e.preventDefault();
        if (!confirm('Вы уверены?')) return;
        Ngn.loading(true);
        new Request({
          url: this.interface.options.controllerPath + '/ajax_deleteBlock',
          onComplete: function() {
            Ngn.loading(false);
            el.destroy();
          }
        }).get({
            blockId: this.id
          });
      }.bind(this));
    }
    if (this.interface.allowGlobalBtn) {
      Ngn.Btn.flag2(parseInt(this.eBlock.get('data-global')), {
        title: 'Сделать локальным',
        cls: 'global',
        url: this.interface.options.controllerPath + '/ajax_setGlobal/' + this.id + '/' + 0
      }, {
        title: 'Сделать глобальным',
        cls: 'local',
        url: this.interface.options.controllerPath + '/ajax_setGlobal/' + this.id + '/' + 1
      }).inject(btnDelete, 'after');
    }
  },

  reload: function() {
    this.eBlock.addClass('loading');
    new Ngn.Request({
      url: this.interface.options.controllerPath + '/ajax_getBlock/' + this.id,
      onComplete: function(html) {
        this.eBlock.removeClass('loading');
        this.eBlock.getElement('.bcont').set('html', html);
      }.bind(this)
    }).get();
  },

  replaceEditBlockBtn: function(opts, func) {
    var eCur = this.eEditBlock.getElement('.' + opts.name);
    if (eCur) eCur.dispose();
    this.addEditBlockBtn(opts, func);
  },

  addEditBlockBtn: function(opts, func) {
    Ngn.Btn.btn(opts).inject(this.eEditBlock, 'top').addEvent('click', function(e) {
      e.preventDefault();
      func();
    });
  }

});

Ngn.Pb.dialogOptions = {};

Ngn.Pb.dialogOptions.text = {
  vResize: Ngn.Dialog.VResize.Wisiwig,
  width: 450
};

Ngn.Pb.dialogOptions.subPages = {
  width: 350
};

Ngn.Pb.dialogOptions.textAndImage = {
  width: 450
};

/*
 Ngn.Pb.Text = new Class({
 initialize: function(eForm) {
 Ngn.Dialog.dialogs.getValues().getLast().dialog.setStyle('width', '800px');
 //Ngn.Dialog.dialogs.getValues().getLast().message.setStyle('height', '600px');
 Ngn.Dialog.dialogs.getValues().getLast().screenСenter();
 eForm.getElement('.type_wisiwig').setStyle('padding', 0);
 eForm.getElement('.type_wisiwig > p.label').setStyle('display', 'none');
 }
 });
 */
