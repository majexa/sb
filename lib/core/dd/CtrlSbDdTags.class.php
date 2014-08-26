<?php

class CtrlSbDdTags extends CtrlSbAdmin {

  function action_json_getTree() {
    Sflm::frontend('css')->addPath('i/css/common/tree.css');
    $this->json['tree'] = (new ClientTree(DdTags::getByGroupId($this->req->param(2))))->getTree();
  }

}