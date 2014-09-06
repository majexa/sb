<html>
<head>
  <?= Sflm::getTags() ?>
  <style>
    img {
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>
  <? foreach ($d['captures'] as $v) { ?>
    <h2><?= $v['title'] ?></h2>
    <? foreach ($v['items'] as $img) { ?>
      <img src="<?= $img ?>" />
    <? } ?>
  <? } ?>
</body>
</html>