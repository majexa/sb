<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title><?= $d['pageHeadTitle'] ?></title>
  <?= Sflm::getTags() ?>
  <?= $d['head'] ?>
  <style>
    <?= CssCore::btnColors('#F1F0DF') ?>
  </style>
  <script src="/i/js/tiny_mce/tiny_mce.js"></script>
  <script>
    Ngn.isAdmin = <?= Misc::isAdmin() ? 'true' : 'false' ?>;
  </script>
</head>
