<?php

class CtrlSb extends CtrlCommon {

  function action_default() {
    $this->d['layoutN'] = 3;
    $this->d['page'] = ['id' => 1];
    $this->d['tpl'] = 'default';
  }

}