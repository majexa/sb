<? $this->tpl('head', $d) ?>

<body>
<div id="layout" class="layout_<?= $d['layoutN'] ?><?= $d['page']['module'] ? ' pm_'.$d['page']['module'] : '' ?><?= $d['page']['home'] ? ' home' : '' ?> pn_<?= $d['page']['name'] ?> action_<?= $d['action'] ?><?= $d['settings']['defaultAction'] == 'blocks' ? ' blocksLayout' : '' ?>">
<div class="lwrapper">
  <div class="container">
    <?= StmCore::slices() ?>
    <a href="/" id="logo" title="<?= SITE_TITLE ?>"><span><?= SITE_TITLE ?></span></a>
    <? if (StmCore::getCurrentThemeData()['topEnable']) { ?>
    <div class="span-24 last" id="top">
      <?= Html::baseDomainLinks($this->getTpl('top', $d)) ?>
    </div>
    <? } ?>
    <div class="span-24 last">
      <div class="menuCont">
        <div class="hMenu mainmenu" id="menu">
          <?= SiteMenu::menu($d['oController']) ?>
          <div class="clear"><!-- --></div>
        </div>
      </div>
      <div class="pageLayout">
        <!-- Page Layout Begin -->
        <?
        if (!empty($d['oController']->userGroup)) {
          print Html::subDomainLinks($this->getTpl('pageLayout/'.$d['layoutN'], $d), $d['oController']->userGroup['name']);
        } else {
          //print (Tt()->exists('pageLayout/'.$d['layoutN']) and 0) ?
          //print Tt()->getTpl('pageLayout/'.$d['layoutN'], $d);
          print PageLayout::autoHtml($d['layoutN'], $d['page']['id'], $d['oController']);
        }
        ?>
        <div class="clear"><!-- --></div>
        <!-- Page Layout End -->
      </div>
    </div>
  </div>
  <div class="push"></div>
</div>
</div>

<div id="footer">
  <div class="fContainer">
    <div class="body">
      <? //$this->tpl('footer/'.StmCore::getCurrentThemeData()->data['data']['footerType']) ?>
    </div>
  </div>
</div>

<script type="text/javascript">
Ngn.sd.page = <?= Arr::jsObj($d['page']->r) ?>;
</script>

</body>
</html>
