<?php

class TestPageModuleNews extends TestUiPageModule {

  function test() {
    $strName = 'news';
    if (($page = DbModelCore::get('pages', $strName, 'name'))) PageModulePage::get($page)->delete();
    PageModule::get('news')->create(['title' => 'news']);
    $page = DbModelCore::get('pages', $strName, 'name');
    $im = new DdItemsManagerPage($page, new DdItems($strName), new DdForm(new DdFields($strName), $strName));
    for ($i = 0; $i <= 3; $i++) {
      $im->create([
        'title'       => 'sample',
        'pretext'     => TestRunnerNgn::largeTextFixture(),
        'text'        => TestRunnerNgn::largeTextFixture(),
        'category'    => 'рубрика 1',
        'datePublish' => explode('.', date('d.m.Y.H.i')),
        'image'       => TestRunnerNgn::tempImageFixture()
      ]);
    }
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['thenUrl', $strName],
      // ['checkText', '#ti'.$category, 'Категория 1 (4)']
    ]);
  }

}