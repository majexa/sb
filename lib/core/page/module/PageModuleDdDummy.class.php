<?php

class PageModuleDdDummy extends PageModuleDd {

  public $title = 'Пустышка';
  public $oid = 500;
	
  protected $ddFields = [
    [
      'title' => 'Заголовок',
      'name' => 'title',
      'type' => 'text'
    ]
  ];
  
}