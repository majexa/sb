<?php

class TestCasperCreateTextBlock extends TestCasperProjectAbstract {

  function test() {
    $this->casper([
      ['thenUrl', ''],
      ['click', 'a.add'],
      ['waitForDialog'],
      ['click', '.opt_text input'],
      ['click', '.ok .btn'],
      ['waitForDialog'],
      ['click', '.ok .btn'],
      ['waitForDialogClose'],
      //['testPresence', '']
    ]);
  }

}
