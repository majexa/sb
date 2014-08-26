<?php

class CtrlSbPages extends CtrlSbAdmin {

  function action_json_getTree() {
    Sflm::frontend('css')->addPath('i/css/common/tree.css');
    $this->json['tree'] = (new PagesTree)->getTree();
  }

  function action_ajax_delete() {
    PageModulePage::get($this->req['id'])->delete();
  }

}