window.addEvent('domready', function() {
  if (Ngn.isAdmin) {
    new Ngn.Pb.Interface({
      controllerPath: '/sbc/pageBlocks/' + Ngn.sb.page.id
    });
  }
  Ngn.addBtnsAction('a.userReg', function() {
    new Ngn.Dialog.Auth({
      selectedTab: 1
    });
  });
  Ngn.addBtnAction('.topBtns .new', function(eBtn) {
    c(eBtn.get('data-url'));
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
});
