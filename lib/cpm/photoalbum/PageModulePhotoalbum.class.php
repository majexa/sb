<?php

class PageModulePhotoalbum extends PageModuleDd {

  public $title = 'Фотоальбом';
  public $oid = 20;

  protected $ddFields = [
    [
      'title' => 'Изображение',
      'name'  => 'image',
      'type'  => 'imagePreview'
    ],
    [
      'title' => 'Описание',
      'name'  => 'text',
      'type'  => 'wisiwigSimple'
    ],
  ];

  protected function prepareNode(array $node) {
    $node = parent::prepareNode($node);
    $node['settings']['smW'] = 120;
    $node['settings']['smH'] = 120;
    return $node;
  }

}
