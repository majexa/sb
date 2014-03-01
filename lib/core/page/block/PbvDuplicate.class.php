<?php

class PbvDuplicate extends PbvAbstract {

  /**
   * Duplicate model
   * 
   * @var DbModel
   */
  protected $oPBDM;

  protected function init() {
    $this->oPBDM = DbModelCore::get('pageBlocks', $this->pageBlock['settings']['duplicateBlockId']);
  }
  
  function _html() {
    return O::get(ClassCore::nameToClass('Pbv', $this->oPBDM->type), $this->oPBDM)->_html();
  }
  
  function getData() {
  }

}