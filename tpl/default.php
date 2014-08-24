<div id="top">
  <div class="span-5">&nbsp;</div>
  <div class="span-19 last">
    <div class="mainMenu">
      <?= (new PagesMenuUl)->html() ?>
    </div>
  </div>
  <div class="clear"></div>
</div>


<?= $d['body'] ?>

<script>
  Ngn.toObj('Ngn.sb');
  Ngn.sb.page = <?= Arr::jsObj($d['page']->r) ?>;
</script>
