<?php

class PageModuleStaticBlocks {

  public $blocks = [];
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
    foreach ($this->blocksObjs as $o) {
      /* @var PmsbAbstract $o */
      if (($blocks = $o->blocks()) !== false) $this->blocks = array_merge($this->blocks, $blocks);
    }
  }

  function processDynamicBlockModels(array &$blockModels) {
    foreach ($this->blocksObjs as $o) {
      $o->processDynamicBlockModels($blockModels);
    }
  }

}