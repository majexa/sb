<?php

/**
 * Page controller settings action
 */
abstract class Pcsa {
  
  /**
   * @var DbModelPages
   */
  public $page;
  
  function __construct(DbModelPages $page) {
    $this->page = $page;
  }
  
  abstract function action(array $initSettings);
  
}