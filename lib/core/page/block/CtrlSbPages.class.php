<?php

class CtrlSbPages extends CtrlSbAdmin {

  function action_json_getTree() {
    $this->json['tree'] = (new PagesTree)->getTree();
  }

}