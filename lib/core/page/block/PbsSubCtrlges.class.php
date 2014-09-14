<?php

class PbsSubCtrlges extends PbsSubCtrlgesAbstract {

  static $title = 'Подразделы определенного раздела';
  
  protected function initFields() {
    $this->fields[] = [
      'title' => 'Раздел',
      'name' => 'pageId',
      'type' => 'pageId',
      'required' => true
    ];
    parent::initFields();
  }

}
