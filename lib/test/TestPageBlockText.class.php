<?php

class TestPageBlockText extends TestCasperSb {

  function testCreate() {
    $this->casper([
      ['thenUrl', ''],
      ['click', 'a.add'],
      ['waitForDialog'],
      ['selectOption', '.name_type select', 'text'],
      ['click', '.ok .btn'],
      ['waitForDialog'],
      ['wait', 1500],
      ['click', '.ok .btn'],
      ['waitForDialogClose'],
      ['thenUrl', ''],
      ['capture'],
      ['checkExistence', '.pbt_text']
    ]);
  }

  function testDelete() {
    $this->casper([
      ['thenUrl', ''],
      ['wait', 500],
      ['click', '.pbt_text .delete'],
      ['wait', 1500],
      ['capture'],
      ['checkNonExistence', '.pbt_text']
    ]);
  }
}
