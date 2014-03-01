<? $this->tpl('head', $d) ?>

<body>
<div id="layout"
     class="layout_<?= $d['layoutN'] ?><?= $d['page']['module'] ? ' pm_'.$d['page']['module'] : '' ?><?= $d['page']['home'] ? ' home' : '' ?> pn_<?= $d['page']['name'] ?> action_<?= $d['action'] ?><?= $d['settings']['defaultAction'] == 'blocks' ? ' blocksLayout' : '' ?>">
  <div class="lwrapper">
    <div class="container">
      <div class="span-24 last">
        <div class="pageLayout">
          <?= PageLayout::autoHtml($d['layoutN'] ?: 1, $d['page']['id'], $d['oController']) ?>
          <div class="clear"><!-- --></div>
        </div>
      </div>
    </div>
    <div class="push"></div>
  </div>
</div>
<script>
<? $this->tpl('common/js', $d) ?>
</script>
</body>
</html>
