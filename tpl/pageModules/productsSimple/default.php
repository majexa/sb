<?

if (empty($d['params'][1])) {
  print "<ul>";
  foreach ($d['tags']['rub'] as $v) {
    print "<li><a href=".$this->getPath(1).'/t2.rub.'.$v['id'].">".$v['title']."</a></li>";
  }
  print "</ul>";
} else {
  $this->tpl('dd/css', $d);
  print Slice::html(
    'beforeDdItems_'.$d['listSlicesId'],
    'Блок перед записями «'.$d['page']['title'].
      (empty($d['listSlicesTitle']) ? '' : ' / '.$d['listSlicesTitle']).'»'
  );
  $oDdLayoutOutput = new DdoPageSite($d['page'], 'siteItems');
  $oDdLayoutOutput->setItems($d['items']);
  $oDdLayoutOutput->canEdit = in_array('edit', $d['allowedActions']);
  $oDdLayoutOutput->premoder = !empty($d['settings']['premoder']);
  Err::noticeSwitch(true);
  if (empty($d['settings']['doNotShowItems'])) {
    if (!empty($d['items'])) {
      print $oDdLayoutOutput->els();
      print '<div class="clear"><!-- --></div>';
    } else {
      print empty($d['page']['settings']['doNotShowNitems']) ? 'Нет записей' : '';
      /*
      print Slice::html('nitems_'.$d['page']['id'].
        (isset($d['tagsSelected'][0]) ?
          '_'.$d['tagsSelected'][0]['id'] : ''),
        isset($d['tagsSelected'][0]) ?
          'Нет записей ('.$d['tagsSelected'][0]['title'].')' : 'Нет записей',
        'Нет записей');
      */
    }
  }

}