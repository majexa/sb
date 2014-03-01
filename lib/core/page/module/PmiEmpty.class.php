<?php

class PmiEmpty extends Pmi {

  public $title = 'Пустой';
  
  function __construct() {
    parent::__construct();
    $this->module = '';
  }

}