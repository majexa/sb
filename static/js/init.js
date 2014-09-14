window.addEvent('domready', function() {
  if (Ngn.isAdmin) {
    document.getElement('.layout').addClass('admin');
    new Ngn.Pb.Interface({
      controllerPath: '/sbc/pageBlocks/' + Ngn.Sb.page.id
    });
  }
  Ngn.addBtnsAction('a.userReg', function() {
    new Ngn.Dialog.Auth({
      selectedTab: 1
    });
  });
  Ngn.addBtnsAction('a.btnAuth', function() {
    new Ngn.Dialog.Auth();
  });
  Ngn.addBtnAction('.topBtns .new', function(eBtn) {
    new Ngn.Dialog.RequestForm({
      id: 'store',
      title: 'Добавление записи',
      url: eBtn.get('data-url'),
      width: 300,
      onSubmitSuccess: function() {
        window.location.reload(true);
      }
    });
  });
  Ngn.addBtnAction('.authBar .btnSubmit', function() {
    $('authForm').submit();
  });
  Ngn.Milkbox.add(document.getElements('.thumb'));

  new Ngn.Sb.DdItems(document.getElements('.ddItems .item'));

  if (Ngn.isAdmin) {
    document.getElements('.slice').each(function(eSlice) {
      var btn = new Element('a', {
        href: '#',
        html: 'ред.'
      }).inject(eSlice);
      btn.addEvent('click', function(e) {
        e.preventDefault();
        new Ngn.Dialog.RequestForm({
          id: 'slice1',
          url: '/sbc/slices/footer/json_edit',
          vResize: Ngn.Dialog.VResize.Wisiwig,
          width: 450
        });
      });
    });

    // edit layout
    /*
    var eTopBtns = document.getElement('.topBtns');
    var eBtn = Elements.from('<div style="display:inline-block; margin-left: 5px;" class="editBlock smIcons bordered blue"><a href="" class="editLayout dynamic"><i></i></a></div>')[0];
    eBtn.inject(eTopBtns);
    var w = 0;
    eTopBtns.getChildren().each(function(el) {
      w += el.getSizeWithMargin().x;
    });
    eTopBtns.setStyle('width', w);
    document.getElement('.topBtns').inject(Ngn.btn2('Редактировать лейаут', 'page'));
     new Ngn.Dialog.RequestForm({
     url: '/sbc/pageLayout/' + Ngn.Sb.page.id + '/json_edit'
     });
    */
  }

});
