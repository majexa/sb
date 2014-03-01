<?php

class PbsCalendar extends PbsDdPage {

  static $title = 'Календарь';
  
  protected function initDefaultFields() {
  }
  
  protected function initFields() {
    $this->fields[] = [
      'name' => 'dateField',
      'title' => 'Поле даты',
      'type' => 'ddDateFields',
      'required' => true
    ];
  }

}