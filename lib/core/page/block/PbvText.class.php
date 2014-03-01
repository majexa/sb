<?php

class PbvText extends PbvAbstract {
  
  static $cachable = true;
  
  function _html() {
    return $this->pageBlock['settings']['text'];
  }
  
}