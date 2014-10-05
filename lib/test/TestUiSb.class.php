<?php

abstract class TestUiSb extends TestCasperProjectAbstract {

  static function enable() {
    return false;
  }

  protected function casper(array $steps, array $options = []) {
    Casper::run(PROJECT_KEY, $steps, array_merge([
      'extension' => SB_PATH.'/casper/extention.js'
    ], $options));
  }

}