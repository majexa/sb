<?php

/**
 * Page Block View User Group
 */
abstract class Pbvug {

  /**
   * @var PbvAbstract
   */
  protected $pbv;

  function __construct(PbvAbstract $pbv) {
    $this->pbv = $pbv;
    $this->init();
  }
  
  protected function init() {}
  
  function getData() {
    return $this->pbv->getData();
  }

}
