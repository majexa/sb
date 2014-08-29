<?php

class PmsbNews extends PmsbAbstract {

  function initBlocks() {
    return;
    $this->addBlock([
      'colN' => 3,
      'type' => 'calendar',
      'html' => false
    ]);
  }

}