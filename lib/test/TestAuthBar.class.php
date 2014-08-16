<?php

class TestAuthBar extends TestCasperSb {

  function test() {
    $this->casper([
      ['thenUrl', ''],
      ['click', 'a.userReg'],
      ['waitForDialog'],
      ['click', '.tab-menu a:first-child'],
      ['wait', 1500],
      ['fillAuthForm'],
      ['click', '.ok .btn'],
      ['wait', 1500],
      ['capture'],
    ]);
  }

}