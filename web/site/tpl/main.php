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
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>
  <? foreach ($d['captures'] as $v) { ?>
    <h2><?= $v['title'] ?></h2>
    <? foreach ($v['items'] as $img) { ?>
      <div class="item">
        <h3><?= $img['title'] ?></h3>
        <img src="<?= $img['path'] ?>" />
      </div>
    <? } ?>
  <? } ?>
</body>
</html>