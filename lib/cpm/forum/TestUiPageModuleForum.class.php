<?php

class TestUiPageModuleForum extends TestUiPageModule {

  function test() {
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
    ]);
  }

}