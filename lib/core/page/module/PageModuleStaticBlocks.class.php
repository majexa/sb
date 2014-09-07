<?php

class PageModuleStaticBlocks {

  public $blocks = [];

  /**
   * @var PmsbAbstract[]
   */
  protected $blocksObjs = [];

  function __construct(CtrlPage $controller) {
    if (empty($controller->page['module'])) return false;
    if (($info = PageModuleCore::getInfo($controller->page['module'])) === false) return;
    $info = $info->getData('blocks');
    if (!empty($info['disableModuleParentBlocks'])) {
      if (($class = PageModuleCore::getClass($controller->page['module'], 'Pmsb')) !== false) {
        $this->blocksObjs[] = O::get($class, $controller);
      }
    } else {
      foreach (PageModuleCore::getAncestorClasses($controller->page['module'], 'Pmsb') as $class) {
        $this->blocksObjs[] = O::get($class, $controller);
      }
    }
    foreach ($this->blocksObjs as $o) $this->blocks = array_merge($this->blocks, $o->blocks);
  }

  function processDynamicBlockModels(array &$blockModels) {
    foreach ($this->blocksObjs as $o) {
      $o->processDynamicBlockModels($blockModels);
    }
  }

}