<?php

class TstPageBlockText extends TestCasperSb {

  function testCreate() {
    $this->casper([
      ['thenUrl', ''],
      ['click', 'a.add'],
      ['waitForDialog'],
      ['selectOption', '.name_type select', 'text'],
      ['wait', 2000],
      ['capture'],
      ['wait', 2000],
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

  function tstDelete() {
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