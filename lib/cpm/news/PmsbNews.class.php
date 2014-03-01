<?php

class PmsbNews extends PmsbAbstract {

  function initBlocks() {
    $this->addBlock([
      'colN' => 3,
      'type' => 'calendar',
      'html' => false
    ]);
  }

}