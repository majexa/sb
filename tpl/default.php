<?= $d['body'] ?>

<script>
  Ngn.toObj('Ngn.sb');
  Ngn.sb.page = <?= Arr::jsObj($d['page']->r) ?>;
  Ngn.isAdmin = <?= Misc::isAdmin() ? 'true' : 'false' ?>;

  window.addEvent('domready', function() {

  });
</script>
