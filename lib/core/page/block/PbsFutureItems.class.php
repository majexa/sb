<?php

class PbsFutureItems extends PbsItems {
  
  static $title = 'Записи в будующем';
  
  protected function initFields() {
    parent::initFields();
    $this->fields[] = [
      'name' => 'dateField',
      'title' => 'Поле даты',
      'type' => 'ddDateFields',
      'required' => true
    ];
  }

}
