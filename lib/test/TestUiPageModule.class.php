<?php

abstract class TestUiPageModule extends TestUiSb {

  protected function casper(array $steps) {
    parent::casper($steps, [
      'captureFolder' => 'pageModules/'.$this->captureFolderName()
    ]);
  }

  protected function captureFolderName() {
    return lcfirst(Misc::removePrefix('TestPageModule', get_class($this)));
  }

}