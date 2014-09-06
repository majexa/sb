<html>
<head>
  <?= Sflm::getTags() ?>
  <style>
    ul a {
      font-size: 20px;
      display: inline-block;
      margin-right: 20px;
    }
  </style>
</head>
<body>
<div class="scenarios">
  <ul>
  <? foreach ($d['scenarios'] as $v) { ?><li><a href="<?= $v['link'] ?>" data-id="<?= $v['id']?>"><?= $v['title'] ?></a></li><? } ?>
  </ul>
  <script>
    document.getElements('.scenarios a').each(function(eA) {
      var eLi = eA.getParent();
      var id = eA.get('data-id');
      new Element('a', {html: 'Открыть сценарий', href: '#'}).inject(eLi).addEvent('click', function(e) {
        e.preventDefault();
        new Ngn.Request.JSON({
          url: '/json_open/' + id,
          onComplete: function(r) {
            document.getElement('.text').set('html', r.text);
          }
        }).send();
      });
      new Element('a', {html: 'Выполнить сценарий', href: '#'}).inject(eLi).addEvent('click', function(e) {
        e.preventDefault();
        new Ngn.Request.JSON({
          url: '/json_run/' + id,
          onComplete: function(r) {
            document.getElement('.result').set('html', r.text);
          }
        }).send();
      });
      Ngn.btn1('').inject(eLi);
    });
  </script>
</div>
<div class="text">
  --- text ---
</div>
<div class="result">
  --- result ---
</div>
</body>
</html>