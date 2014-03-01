<?php

class PbvCurSubPages extends PbvSubPagesAbstract {

  protected function getPageId() {
    return isset($this->ctrl->page['pathData'][1]) ?
      $this->ctrl->page['pathData'][1]['id'] : false;
  }
  
}