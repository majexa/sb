<?

// @title Страница одной записи  

$ddo = $d['ddo'];
$ddo->ddddByType['wisiwig'] = $ddo->ddddByType['wisiwigSimple'] = '$v';
$ddo->ddddByType['image'] = '$v ? `<a href="`.$v.`" target="_blank" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `md_`).`" /></a><div class="clear"><!-- --></div>` : ``';
if ($d['settings']['setItemsOnItem'] and ($d['page']['module'] == 'photo' or $d['page']['module'] == 'photoalbum_slave')) {
  list($prevId, $nextId) = Arr::proximity(array_keys($d['items']), $d['item']['id'], true);
  $ddo->ddddByType['image'] = '$v ? `
  '.($prevId >= 0 ? '<a href="'.$this->getPath(1).'/'.$prevId.'" class="btn btn1">&nbsp;« Предыдущая&nbsp;</a>&nbsp' : '').'
  '.($nextId >= 0 ? '<a href="'.$this->getPath(1).'/'.$nextId.'" class="btn btn1">&nbsp;Следующая »&nbsp;</a>' : '').'
  <div class="clear"><!-- --></div>
  <a href="`.$v.`" target="_blank" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `md_`).`" /></a><div class="clear"><!-- --></div>` : ``';
} else
  $ddo->ddddByType['image'] = '$v ? `<a href="`.$v.`" target="_blank" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `md_`).`" /></a><div class="clear"><!-- --></div>` : ``';
$ddo->ddddByName['title'] = '';
$ddo->ddddItemsBegin = '`<div class="element n_fieldName t_fieldType">`';

?>

<div class="contentBody<?= $d['action'] == 'showItem' ? ' oneItem' : '' ?> str_<?= $d['page']['strName'] ?>">
  <?= $ddo->els() ?>
</div>

<?

$this->tpl('dd/beforeComments', $d, true);

if ($d['showCommentsAfterItem'] and isset($d['oController']->subControllers['comments'])) {
  $this->tpl(
    $d['oController']->subControllers['comments']->d['tpl'],
    $d['oController']->subControllers['comments']->d
  );
}

?>