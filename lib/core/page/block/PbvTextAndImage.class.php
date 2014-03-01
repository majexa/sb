<?php

class PbvTextAndImage extends PbvAbstract {

  protected $extendImageData = true;
  
  protected function init() {
    Sflm::flm('css')->addLib('s2/css/common/pageBlocks.css?blockType=textAndImage');
  }

}
