<?php

class PcpDdItem extends PcpDd {
  
  public $title = 'Запись';
  
  function getProperties() {
    $pr = parent::getProperties();
    $pr = Arr::dropBySubKeys($pr, 'name', 'k');
    return $pr;
  }
  
}