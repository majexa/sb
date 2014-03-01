<?php

class PbsTpl extends PbsAbstract {

  static $cachable = false;
  
  static $title = 'Шаблон';
  
  protected function initFields() {
    $this->fields = [
      [
        'title' => 'Имя шаблона',
        'name' => 'name',
        'type' => 'name',
        'required' => true
      ]
    ];
  }
  

}