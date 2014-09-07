<?php

/**
 * Page module static blocks
 */
abstract class PmsbAbstract implements ProcessDynamicPageBlock {

  /**
   * CtrlPage
   */
  protected $ctrl;
  
  protected $name;

  /**
   * @var array
   */
  public $blocks = [];
  
  public $module, $enable = true;
  
  function __construct(CtrlPage $ctrl) {
    $this->ctrl = $ctrl;
    $this->name = ClassCore::classToName('Pmsb', get_class($this));
    $this->init();
    if ($this->enable) $this->initBlocks();
  }

  protected function init() {}

  abstract protected function initBlocks();
  
  protected function addBlock(array $data) {
    $data['className'] = $this->name;
    Misc::checkEmpty($data['type']);
    if ($data['html'] === false) {
      $class  = 'Pbv'.ucfirst($data['type']);
      /* @var PbvAbstract $pbv */
      $pbv = new $class(null, $this->ctrl);
      $data['html'] = $pbv->html();
    }
    $this->blocks[] = $data;
  }

  function processDynamicBlockModels(array &$blockModels) {
    if (!$this->enable) return;
    $this->_processDynamicBlockModels($blockModels);
  }
  
  function _processDynamicBlockModels(array &$blockModels) {}

}
