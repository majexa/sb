<?php

class PbsSlaveSubItems extends PbsItems {

  static $title = 'Slave-записи текущей master записи';
  
  protected function initPreFields() {
  }
  
  function getHiddenParams() {
    Arr::checkEmpty($this->options, 'pageId');
    return ['pageId' => $this->options['pageId']];
  }
  
}