<?php

class PbvTpl extends PbvAbstract {

  function _html() {
    return Tt()->getTpl('pageBlocks/tpl/'.$this->pageBlock['settings']['name'],
      ['controller' => $this->ctrl]);
  }

}
