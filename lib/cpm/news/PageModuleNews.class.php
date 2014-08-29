<?php

class PageModuleNews extends PageModuleDd {

  public $title = 'Новости';
  public $oid = 20;

  protected $ddFields = [
    [
      'title'    => 'Заголовок',
      'name'     => 'title',
      'type'     => 'typoText',
      'required' => true
    ],
    [
      'title' => 'Изображение',
      'name'  => 'image',
      'type'  => 'imagePreview'
    ],
    [
      'title' => 'Рубрики',
      'name'  => 'category',
      'type'  => 'ddTags'
    ],
    [
      'title' => 'Анонс',
      'name'  => 'pretext',
      'type'  => 'wisiwigSimple'
    ],
    [
      'title' => 'Текст',
      'name'  => 'text',
      'type'  => 'wisiwigSimple'
    ],
    [
      'title' => 'Дата публикации',
      'name'  => 'datePublish',
      'type'  => 'datetime'
    ]
  ];

  protected function prepareNode(array $node) {
    $node = parent::prepareNode($node);
    $node['settings']['dateField'] = 'datePublish';
    $node['settings']['smW'] = 80;
    $node['settings']['smH'] = 50;
    $node['settings']['mdW'] = 200;
    $node['settings']['mdH'] = 150;
    return $node;
  }

}
