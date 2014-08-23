<?php

class PageModuleEmpty extends PageModule {

  public $title = 'Пустой';
  
  function __construct() {
    parent::__construct();
    $this->module = '';
  }

}