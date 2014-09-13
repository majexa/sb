<?php

class TestPageModuleStore extends TestUiPageModule {

  function test() {
    $strName = 'magazin';
    if (($page = DbModelCore::get('pages', $strName, 'name'))) PageModulePage::get($page)->delete();
    PageModule::get('store')->create();
    $page = DbModelCore::get('pages', $strName, 'name');
    $im = new DdItemsManagerPage($page, new DdItems($strName), new DdForm(new DdFields($strName), $strName));
    $categories = DdTags::get($strName, 'category')->getData();
    $category = 0;
    for ($i = 0; $i <= 3; $i++) {
      $category = $categories[array_rand($categories)]['id'];
      $im->create([
        'title'    => 'sample',
        'category' => $category,
        'image'    => TestRunnerNgn::tempImageFixture(),
        'price'    => 123
      ]);
    }
    $this->casper([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['~thenUrl', $strName],
      ['checkExistence', '#ti'.$category],
      ['~click', '.pbt_tags .edit'],
      ['thenUrl', $strName],
      ['~click', '.pbt_tags .tag2'],
    ]);
  }

}