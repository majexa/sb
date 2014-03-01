<?

if ($_GET['completeAction'] == 'new') {
  print Slice::html(
    'complete_'.$d['page']['id'],
    'Сообщение об успешной отправке',
    'Ваш вопрос был отправлено администратору'
  );
} else $this->tpl('dd/complete', $d);