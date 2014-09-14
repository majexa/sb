<?php

class SubCtrlComments extends SubCtrlMsgs {
  
  protected function initMsgs() {
    $this->oMsgs = new Comments($this->id1, $this->id2);
  }
  
}