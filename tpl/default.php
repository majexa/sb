<?= $d['body'] ?>

<? //prr($d['page']) ?>

<script>
  Ngn.toObj('Ngn.sb');
  Ngn.sb.page = <?= Arr::jsObj($d['page']->r) ?>;
  Ngn.isAdmin = <?= Misc::isAdmin() ? 'true' : 'false' ?>;
</script>
