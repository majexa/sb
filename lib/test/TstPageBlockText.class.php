<?php

class TstPageBlockText extends TestCasperSb {

  function testCreate() {
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
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
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['wait', 500],
      ['click', '.pbt_text .delete'],
      ['wait', 1500],
      ['capture'],
      ['checkNonExistence', '.pbt_text']
    ]);
  }
}
