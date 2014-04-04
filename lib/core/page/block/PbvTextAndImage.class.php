<?php

class PbvTextAndImage extends PbvAbstract {

  protected $extendImageData = true;
  
  protected function init() {
    Sflm::frontend('css')->addLib('s2/css/common/pageBlocks.css?blockType=textAndImage');
  }

}
