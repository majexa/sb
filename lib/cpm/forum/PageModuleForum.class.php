<?php

class PageModuleForum extends PageModuleDd {

  public $title = 'Форум';
  public $oid = 20;

  protected $ddFields = [
    [
      'title'    => 'Заголовок',
      'name'     => 'title',
      'type'     => 'typoText',
      'required' => true
    ],
    [
      'title' => 'Тэги',
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
  ];

}
