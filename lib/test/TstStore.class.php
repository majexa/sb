<?php

class TstStore extends TestCasperSb {

  function test() {
    // не круто, что одна команда
    // tst c createProject sb
    // заменяет другую:
    // pm localServer createProject test default sb
    PageModule::get('store')->delete(DbModelCore::get('pages', 'store', 'name')['id']);
    PageModule::get('store')->create();
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['thenUrl', 'store'],
      ['capture'],
    ]);

    //output(2);
    //$id = PageModule::get('store')->create();
    //output(3);
    //$this->casper([['capture']]);
    //output(4);
    //PageModule::get('store')->delete($id);
    //output(5);
  }

}