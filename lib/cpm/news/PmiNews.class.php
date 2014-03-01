<?php

class PmiNews extends PmiDd {

  public $title = 'Новости';
  public $oid = 20;

  protected $ddFields = [
    [
      'title'    => 'Заголовок',
      'name'     => 'title',
      'type'     => 'typoText',
      'required' => true
    ], [
      'title' => 'Изображение',
      'name'  => 'image',
      'type'  => 'imagePreview'
    ], [
      'title'    => 'Анонс',
      'name'     => 'pretext',
      'type'     => 'wisiwig',
      'required' => true
    ], [
      'title' => 'Текст',
      'name'  => 'text',
      'type'  => 'wisiwig'
    ], [
      'title' => 'Дата публикации',
      'name'  => 'datePublish',
      'type'  => 'datetime'
    ], [
      'title' => 'dummy',
      'name'  => 'text_body',
      'type'  => 'floatBlock'
    ],
  ];

  protected function getSettings() {
    return array_merge(parent::getSettings(), [
        'dateField' => 'datePublish',
        'itemTitle' => 'новость',
        'smW'       => 80,
        'smH'       => 50,
        'mdW'       => 200,
        'mdH'       => 150
      ]);
  }

}
