<?php

class CtrlSbAdmin extends CtrlDefault {

  protected function getParamActionN() {
    return 3;
  }

  protected $page;

  protected function init() {
    if (!Misc::isAdmin()) throw new AccessDenied;
    $this->hasOutput = false;
  }

}