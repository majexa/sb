<div style="margin-bottom: 10px">
  <div class="span-5">&nbsp;</div>
  <div class="span-16 last">
    <div class="mainMenu">
      <?= (new PagesMenuUl)->html() ?>
    </div>
  </div>
  <div class="clear"></div>
</div>

<?= $d['body'] ?>

<div class="clear"></div>
<div class="footer">
  <!--
  <style>
    .slice a {
      float: none !important;
      display: inline-block;
    }
  </style>
  <span class="slice">It's a footer. Nooter</span>
  -->
</div>

<script>
  Ngn.toObj('Ngn.sb');
  Ngn.Sb.page = <?= Arr::jsObj($d['page']->r) ?>;
</script>
