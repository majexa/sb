<?php

class PbvSubPages extends PbvSubPagesAbstract {

  protected function getPageId() {
    return $this->pageBlock['settings']['pageId'];
  }

}