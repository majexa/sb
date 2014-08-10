if (!Ngn) var Ngn = {};
Ngn.isGod = <?= Misc::isGod() ? 'true' : 'false' ?>;
Ngn.isAdmin = <?= Misc::isAdmin() ? 'true' : 'false' ?>;
Ngn.toObj('Ngn.sd.page', { id: <?= $d['page']['id'] ?> });
//Ngn.sd.page = { id: <?= $d['page']['id'] ?> }

var btnCreate = $('btnCreate');
<? if ($d['authToCreate']) { ?>
  // Окно авторизации при нажатие на кнопку "Новая запись"
  if (btnCreate) {
    btnCreate.addEvent('click', function(e){
      e.preventDefault();
      new Ngn.Dialog.Auth({
        completeUrl: btnCreate.get('href')
      });
    });
  }
<? } else { ?>
  if (btnCreate) {
    btnCreate.addEvent('click', function(e){
      e.preventDefault();
      new Ngn.sb.DdItems.Dialog({
        url: this.get('href').replace('a=new', 'a=json_new'),
        onSubmitSuccess: function() {
          window.location.reload(true);
        },
        width: 400
      });
    });
  }
<? } ?>

// Формы
document.getElements('.apeform form').each(function(eForm) {
  Ngn.Form.factory(eForm);
});

<? if (Misc::isAdmin() and AdminModule::isAllowed('pageBlocks')) { ?>
  Ngn.toObj('Ngn.Pb.interface', new Ngn.Pb.Interface({
  wrapperSelector: '.pageLayout',
  controllerPath: '/sbc/pageBlocks/' + Ngn.sd.page.id,
  colBodySelector: '.pageBlocks',
  disableDeleteBtn: true
  }));
<? } ?>
window.addEvent('domready', function() {
  // Выравнивание высоты tile-записей
  new Ngn.sb.DdItems.EquailSizes('.ddil_tile .ddItems .item');
  var ddItems = document.getElement('.mainBody .ddItems');
  <? if (($userId = Auth::get('id')) and $d['isItemsController']) { ?>
    if (ddItems) new Ngn.sb.DdItems(ddItems.getElements('.item'), {
    curUserId: <?= $userId ?>,
    isAdmin: <?= (int)Misc::isAdmin() ?>,
    editPath: <?= Arr::jsValue(empty($d['editPath']) ? null : $d['editPath']) ?>,
    sortables: <?= $d['page']['settings']['order'] == 'oid' ? 'true' : 'false' ?>
    });
  <? } ?>
});