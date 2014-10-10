<?php

class CtrlAdminPageDdItems extends CtrlAdminDdItems {

  protected $page;

  protected function getName() {
    return 'ddItems';
  }

  protected function init() {
    $this->page = DbModelCore::get('pages', $this->req->param(2));
    parent::init();
  }

  protected function getStrName() {
    return $this->page['strName'];
  }

  protected function _getIm() {
    return new DdItemsManagerPage($this->page, $this->items(), $this->objectProcess(new DdForm(new DdFields($this->getStrName(), ['getDisallowed' => true]), $this->getStrName()), 'form'));
  }

}