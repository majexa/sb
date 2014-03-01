<?php

/**
 * Page Module Priviliges
 */
abstract class Pmp extends ArrayAccesseble {

  protected $userId;

  function __construct($userId) {
    $this->userId = $userId;
    $this->init();
  }
  
  abstract protected function init();

}
