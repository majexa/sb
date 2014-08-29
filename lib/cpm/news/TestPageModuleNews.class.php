<?php

class TestPageModuleNews extends TestCasperSb {

  function test() {
    $strName = 'news';
    if (($page = DbModelCore::get('pages', $strName, 'name'))) PageModulePage::get($page)->delete();
    PageModule::get('news')->create(['title' => 'news']);
    $page = DbModelCore::get('pages', $strName, 'name');
    $im = new DdItemsManagerPage($page, new DdItems($strName), new DdForm(new DdFields($strName), $strName));
    for ($i=0; $i<=3; $i++) {
      $file = TEMP_PATH.'/'.time();
      copy(MORE_PATH.'/lib/test/fixture/image.jpg', $file);
      $im->create([
        'title' => 'sample',
        'text' => 'sample',
        'category' => 'рубрика 1',
        'datePublish' => explode('.', date('d.m.Y.H.i')),
        'image' => [
          'tmp_name' => $file
        ]
      ]);
    }
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['thenUrl', $strName],
      //['checkText', '#ti'.$category, 'Категория 1 (4)']
    ]);
  }

}