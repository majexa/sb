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
  
  public $blocks = [];
  
  public $module, $enable = true;
  
  function __construct(CtrlPage $ctrl) {
    $this->ctrl = $ctrl;
    $this->name = ClassCore::classToName('Pmsb', get_class($this));
    $this->init();
    if ($this->enable) $this->initBlocks();
  }

  protected function init() {}

  /*
  protected function getName() {
    return lcfirst(Misc::removePrefix('Pmsb', get_class($this)));
  }
  
  protected function getTplName() {
    return 'pmsb/'.$this->getName();
  }
  */
  
  abstract protected function initBlocks();
  
  function blocks() {
    return empty($this->blocks) ? false : $this->blocks;
  }
  
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
