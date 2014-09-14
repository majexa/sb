<?php

class TestUiPageModuleNews extends TestUiPageModule {

  function test() {
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['~thenUrl', static::moduleName()],
      // ['checkText', '#ti'.$category, 'Категория 1 (4)']
    ]);
  }

}