<?

if ($_REQUEST['completeAction'] == 'new') {
  print Slice::create('complete_', $d['page']['id'], 'Сообщение об успешной отправке');
} else {
  $this->tpl('dd/complete', $d);
}