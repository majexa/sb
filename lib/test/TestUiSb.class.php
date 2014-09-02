<?php

abstract class TestUiSb extends TestCasperProjectAbstract {

  protected function casper(array $steps) {
    Casper::run(PROJECT_KEY, $steps, ['extension' => SB_PATH.'/casper/extention.js']);
  }

}