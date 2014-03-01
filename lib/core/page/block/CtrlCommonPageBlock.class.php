<?php

class CtrlCommonPageBlock extends CtrlCommon {

  function action_ajax_get() {
    $r = PageBlockCore::getBlockHtmlData(
      DbModelCore::get('pageBlocks', $this->req->param(3))
    );
    $this->ajaxOutput = $r['html'];//newarr;
  }

  /*
  function action_ajax_getStatic() {
    $r = PageBlockCore::getStaticBlockHtmlData(
      $this->req->param(3),
      $this->req->param(4)
    );
    $this->ajaxOutput = $r['html'];//newarr;
  }
  */
  
}
