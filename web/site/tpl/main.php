<html>
<head>
  <?= Sflm::getTags() ?>
  <style>
    body {
      margin: 20px;
    }
    h2 {
      margin-top: 10px;
    }
    img {
      width: 160px;
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