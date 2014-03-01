<?php

class PbsLastComments extends PbsAbstract {

  static $title = 'Последние комментарии';
  
  protected function initFields() {
    $this->fields[] = [
      'title' => 'Лимит',
      'name' => 'limit',
      'type' => 'num',
    ];
  }

}