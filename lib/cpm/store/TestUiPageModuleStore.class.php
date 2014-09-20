<?php

class TestUiPageModuleStore extends TestUiPageModule {

  function test() {
    $this->casper([
      // ['checkExistence', '#ti'.$category],
      ['~click', '.pbt_tags .edit'],
      // ['~click', '.edit'],
      ['thenUrl', static::moduleName()],
      ['~click', '.pbt_tags .tag2'],
      ['thenUrl', static::moduleName()],
      ['~click', '.ddItems .f_buyBtn a'],
      ['~thenUrl', '/storeOrder'],
    ]);
  }

}