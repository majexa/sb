<?= $d['body'] ?>

<script>
  Ngn.toObj('Ngn.sb');
  Ngn.sb.page = <?= Arr::jsObj($d['page']->r) ?>;
</script>
