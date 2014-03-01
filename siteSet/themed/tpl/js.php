<? $this->tpl('common/js') ?>

window.addEvent('domready', function() {
  <? if (StmCore::getCurrentThemeData()['topEnable']) {
  if (Config::getVarVar('userReg', 'loginEnable')) $labels[] = 'Логин';
  if (Config::getVarVar('userReg', 'emailEnable')) $labels[] = 'E-mail';
  ?>
  Ngn.site.top.auth.init({
    loginDefaultLabel: '<?= implode(' / ', $labels) ?>',
    privMsgsPath: 'privMsgs'
  });
  <? } ?>

  <? if (Misc::isAdmin() and AdminModule::isAllowed('pageBlocks')) { ?>
  new Ngn.slice.Layout();
  <? } ?>
  
  // Табы
  var eTabs = $('tabs');
  if (eTabs) {
    var tabs = new Ngn.Tabs('tabs', {selector: 'h2[class=tab]'});
    var ul = document.getElement('.tab-menu');
    var submenu = $('submenu');
    submenu.empty();
    ul.inject(submenu);
    new Element('div', {'class':'clear'}).inject(ul);
    var anch = window.location.href.split('#')[1];
    if (anch) {
      if (anch.match(/cmt\d+|msgs/))
        // Конкретный коммент. Перключаем на вкладку комментов
        tabs.selectByName('bmt_comments'); 
      else tabs.selectByName(anch);
    }
  }
  
  // Всплывающее окно авторизации
  $$('a.auth').each(function(el){
    el.addEvent('click', function(e) {
      e.preventDefault();
      new Ngn.Dialog.Auth({
        selectedTab: 0
      });
    });
  });
  

  <?php /*
  
  // Всплывающие подсказки
  //new Tips('.tools a,.tooltips a,.tooltip');
  
  // Кнопки сохранения
  // new Ngn.SubmitButtons();
  
  // Автокомплитеры
  //Ngn.Autocompleters.init();
  document.getElements('input[id^=ac-]').each(function(eInput) {
    new Ngn.Autocompleter(eInput, {
      minLength: 2
    });
  });

  <? if ($d['settings']['showRating']) { ?>
  // Рейтинги
  <? if ($d['action'] == 'list' or $d['action'] == 'blocks') { ?>
  new Ngn.ItemsRating({
    isMinus: <?= Config::getVarVar('rating', 'isMinus') ? 'true' : 'false' ?>,
    maxStars: <?= RatingSettings::getMaxStars() ?>,
    strName: '<?= $d['page']['strName'] ?>',
    allowVotingLog: <?= Config::getVarVar('rating', 'allowVotingLogForAll') ? 'true' : 'false' ?>
  });
  <? } elseif ($d['action'] == 'showItem') { ?>
  new Ngn.ItemRating($('ddRating<?= $d['item']['id'] ?>'), {
    isMinus: <?= Config::getVarVar('rating', 'isMinus') ? 'true' : 'false' ?>,
    maxStars: <?= RatingSettings::getMaxStars() ?>,
    strName: '<?= $d['page']['strName'] ?>',
    allowVotingLog: <?= Config::getVarVar('rating', 'allowVotingLogForAll') ? 'true' : 'false' ?>
  });
  <? } ?>
  <? } ?>
  
  <? if ($d['action'] == 'list') { ?>
  Ngn.videitems();
  <? } ?> 

  <? if (($key = Config::getVarVar('google', 'mapKey', true))) { ?>
  // Замена адресов google-картами
  $$('.t_geoAddress').each(function(el){
    new Ngn.Atlas(el, {
      key: '<?= $key ?>'
    });
  });
  <? } ?>
  
  <? if ($d['calendar'] and $d['action'] == 'list') { ?>
  // Календарь фильтров дат
  var eCalendar = $('ddCalendar');
  if (eCalendar) new Ngn.DdCalendar(eCalendar);
  <? } ?>
  
  */?>
  

  // Лайтбокс
  Ngn.lightbox.add($$('a.lightbox,a.iiLink]'));
  
});

Ngn.regNamespace('Ngn.site.top.briefcase.btns', true);

<?
if (($page = DbModelCore::get('pages', 'myProfile', 'controller')) !== false) {
  $exists = UsersCore::profile($userId);
?>
Ngn.site.top.briefcase.btns.unshift(['profile', '<?= $exists ? 'Редактировать профиль' : '<b>Заполните профиль</b>' ?>', function() {
  new Ngn.Dialog.RequestForm({
    url: '<?= $this->getControllerPath('myProfile').'/'.$userId ?>?a=json_<?= $exists ? 'edit' : 'new' ?>',
    onSubmitSuccess: function() {
      window.location = '<?= $this->getControllerPath('userData').'/'.Auth::get('id') ?>';
    }
  });
}]);
<? } ?>

<? if (Config::getVarVar('role', 'enable')) { ?>
Ngn.site.top.briefcase.btns.push(['role', 'Изменить тип профиля', function() {
  new Ngn.Dialog.RequestForm({
    url: '/c/userRole',
    title: false
  });
}]);
<? } ?>

<? /*if ($this->getControllerPath('userReg', true)) { ?>
Ngn.site.top.briefcase.btns.push(['settings', 'Параметры аккаунта', function() {
  window.location = '<?= $this->getControllerPath('userReg').'/redirectFirstEdit' ?>';
}]);
<? }*/ ?>

<? PageModuleCore::inlineJs($d) ?>

<? //if ($d['oController']->userGroup) $this->tpl('js/userGroup', $d) ?>


<? //if (Config::getVarVar('privMsgs', 'enable')) { ?>
<? //} ?>