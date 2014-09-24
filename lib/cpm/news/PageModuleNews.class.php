<?php

class PageModuleNews extends PageModuleDd {

  public $title = 'Новости';
  public $oid = 20;

  protected function ddFields() {
    return [
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
  }

}
