<?php

class TestPageModuleStore extends TestCasperSb {

  function test() {
    $strName = 'magazin';
    if (($page = DbModelCore::get('pages', $strName, 'name'))) PageModulePage::get($page)->delete();
    PageModule::get('store')->create();
    $page = DbModelCore::get('pages', $strName, 'name');
    $im = new DdItemsManagerPage($page, new DdItems($strName), new DdForm(new DdFields($strName), $strName));
    $category = Arr::first(DdTags::get($strName, 'category')->getData())['id'];
    for ($i=0; $i<=3; $i++) {
      $im->create([
        'title' => 'sample',
        'category' => $category,
        'image' => TestRunnerNgn::tempImageFixture(),
        'price' => 123
      ]);
    }
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['thenUrl', $strName],
      ['checkText', '#ti'.$category, 'Категория 1 (4)']
    ]);
  }

}