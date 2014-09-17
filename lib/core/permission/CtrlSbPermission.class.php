<?php

class CtrlSbPermission extends CtrlSbAdmin {

  protected function init() {
    parent::init();
    $this->page = PageControllersCore::getPageModel($this->req->param(2));
  }

  function action_json_settions() {
    //return $this->newBlockFormAction('PageBlockTypeForm');
  }

}