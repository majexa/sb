window.addEvent('domready', function() {
  new Ngn.Pb.Interface({
    controllerPath: '/sbc/pageBlocks/' + Ngn.sb.page.id
  });
  Ngn.addBtnsAction('a.userReg', function(eBtn) {
    new Ngn.Dialog.Auth({
      selectedTab: 1
    });
  });
});
Ngn.Frm.imagedRadio