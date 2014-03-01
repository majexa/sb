<?php

/**
 * Page module static files
 */
class CtrlCommonPmsf extends CtrlCammon {

  function action_default() {
    $ext = $this->req->param(3);
    header('Content-type: text/'.(Misc::hasSuffix('js', $ext) ? 'javascript' : 'css'));
    $this->hasOutput = false;
    include CORE_PAGE_MODULES_PATH.'/'.$this->req->param(2).'/'.$ext.'.php';
  }

}
