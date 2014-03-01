<!-- основная - дополнительная -->
<div class="span-19 col">
  <div class="body moduleBody<?= $d['bodyClass'] ?>">
    <div class="bcont">
      <? if (!empty($d['submenu'])) { ?>
        <div id="submenu" class="submenu">
          <? $this->tpl('common/menu-ul', $d['submenu']) ?>
        </div>
        <div class="clear"><!-- --></div>
      <? } ?>
      <div class="mainHeader">
        <? $this->tpl('common/pathNav', $d) ?>
        <? $this->tpl('common/pageTitle', $d) ?>
      </div>
      <div class="body moduleBody<?= $d['bodyClass'] ?>">
        <? if ($d['page']['settings']['showSubPages']) print '<div class="subPages">'.Menu::getUlObjById($d['page']['id'], 1)->html().'</div>' ?>
        <? $this->tpl($d['tpl'], $d) ?>
      </div>
    </div>
  </div>
</div>
<div class="span-5 last col">
  <div class="body">
    <?= $d['col2'] ?>
  </div>
</div>
