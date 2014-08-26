<?php

class TestStore extends TestCasperSb {

  function test() {
    if (($page = DbModelCore::get('pages', 'magazin', 'name'))) PageModulePage::get($page)->delete();
    PageModule::get('store')->create();
    $im = DdCore::imDefault('magazin');
    for ($i=0; $i<=3; $i++) {
      $file = TEMP_PATH.'/'.time();
      copy(MORE_PATH.'/lib/test/fixture/image.jpg', $file);
      $im->create([
        'title' => 'sample',
        'category' => 1,
        'image' => [
          'tmp_name' => $file
        ],
        'price' => 123
      ]);
    }
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['thenUrl', 'magazin'],
      //['wait', 500],
      ['capture'],
    ]);
    return;

    //output(2);
    //$id = PageModule::get('store')->create();
    //output(3);
    //$this->casper([['capture']]);
    //output(4);
    //PageModule::get('store')->delete($id);
    //output(5);
  }

}