<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<? $this->tpl('head', $d) ?>
<body>
<div class="layout <?= $d['layoutClass'] ?>">
  <div class="container">
    <a id="logo" href="/"></a>
    <div class="pic"></div>
    <? if ($d['layout'] != 'home' and $d['layout'] != 'inner') { ?>
      <div id="top">
        <div class="span-5"><div class="item siteTitle"><a href="/" class="btnFake"><span><?= SITE_TITLE ?></span></a></div></div>
        <div class="span-16 last">
          <? $this->tpl('top', $d) ?>
        </div>
        <div class="clear"></div>
      </div>
    <? } ?>
    <? $this->tpl($d['tpl'], $d) ?>
  </div>
</div>
</body>
</html>
