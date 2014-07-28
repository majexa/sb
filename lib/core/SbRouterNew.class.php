<?php

class SbRouterNew extends DefaultRouter {

  protected $page;

  function _getController() {
    return (new CtrlPageVDefault($this))->setPage($this->page);
  }

  function dispatch() {
    $this->page = new DbModelVirtual([
      'title' => 'default',
      'active' => 1
    ]);
    return parent::dispatch();
  }

}