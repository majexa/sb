<? $path = isset($d['path']) ? $d['path'] : '/'.$this->getPath(1); ?>
<div class="editBlock smIcons bordered">
  <a href="<?= $path ?>/<?= $d['id'] ?>?a=edit" class="edit" title="Редактировать"><i></i></a>
  <a href="<?= $path ?>/<?= $d['id'] ?>?a=<?= $d['active'] ? 'deactivate' : 'activate' ?>" class="actv <?= $d['active'] ? 'deactivate' : 'activate' ?>" title="<?= $d['active'] ? 'Скрыть' : 'Отобразить' ?>"><i></i></a>
  <a href="<?= $path ?>/<?= $d['id'] ?>?a=delete" class="delete" title="Удалить"><i></i></a>
</div>
