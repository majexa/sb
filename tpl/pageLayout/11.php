<div class="body">
<div class="span-24 last">
  <div class="mainHeader">
    <? $this->tpl('common/pathNav', $d) ?>
    <? $this->tpl('common/pageTitle', $d) ?>
  </div>
</div>
<div class="span-14 col">
  <div class="mainBody">
    123
    <? $this->tpl($d['tpl'], $d) ?>
  </div>
</div>
</div>
<div class="span-10 last col">
  <div class="body">
    <?= $d['col2'] ?>
  </div>
</div>
