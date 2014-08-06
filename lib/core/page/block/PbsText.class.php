<?php

class PbsText extends PbsAbstract {

  static $title = 'Текст';
  
  //protected $hasJsInit = true;
  
  protected function initDefaultFields() {
  }
  
  protected function initFields() {
    $this->fields[] = [
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'wisiwigSimple',
    ];
  }

}