<?php

class CtrlCommonPageTree extends CtrlCammon {

  protected $defaultAction = 'json_getTree';

  function action_json_getTree() {
    $this->json = O::get('NgnTreePages')->getTree();
  }

  function action_json_getNode() {
    $this->json = DbModelCore::get('pages', $this->req->param(3))->r;
  }
  
}
