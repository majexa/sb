<?php

class PcpItemsMaster extends PcpDdItems {

  public $title = 'Записи (master)';

  function getProperties() {
    return Arr::append(parent::getProperties(), [
      [
        'name' => 'slavePageId', 
        'title' => 'Slave-раздел', 
        'type' => 'num', 
        'maxlength' => 50,
        'required' => true
      ]
    ]);
  }

}