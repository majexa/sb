<?php

class PmiBlog extends PmiDd {

  public $title = 'Блог';
  public $oid = 20;

  protected $ddFields = [
    [
      'title' => 'Заголовок',
      'name'  => 'title',
      'type'  => 'typoText'
    ],
    [
      'title' => 'Текст',
      'name'  => 'text',
      'type'  => 'wisiwig'
    ]
  ];

}
