<?php

class CtrlCommonPageTree extends CtrlCammon {

  protected $defaultAction = 'json_getTree';

  function action_json_getTree() {
    Sflm::frontend('css')->addPath('i/css/common/tree.css');
    $this->json['tree'] = (new PagesTree)->getTree();
  }

  function action_json_getNode() {
    $this->json = DbModelCore::get('pages', $this->req->param(3))->r;
  }
  
}
