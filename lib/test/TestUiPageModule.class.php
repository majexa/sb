<?php

abstract class TestUiPageModule extends TestUiSb {

  protected function casper(array $steps) {
    if (TestCore::$debug) {
      parent::casper($steps);
      return;
    }
    $steps = array_merge([
      ['thenUrl', '?authLogin=admin&authPass=1234'],
      ['~thenUrl', static::moduleName()],
      ['~click', '.ddItems .editBlock .edit'],
      ['thenUrl', static::moduleName()],
    ], $steps);
    parent::casper($steps, [
      'captureFolder' => 'pageModules/'.static::moduleName()
    ]);
  }

  static function moduleName() {
    return ClassCore::classToName('TestUiPageModule', get_called_class());
  }

  static $itemsLimit = 10;

  static function setUpBeforeClass() {
    parent::setUpBeforeClass();
    $moduleName = static::moduleName();
    if (($page = DbModelCore::get('pages', $moduleName, 'name'))) PageModulePage::get($page)->delete();
    PageModule::get($moduleName)->create(['title' => $moduleName]);
    $page = DbModelCore::get('pages', $moduleName, 'name');
    $im = new DdItemsManagerPage($page, new DdItems($moduleName), new DdForm(new DdFields($moduleName), $moduleName));
    for ($i = 0; $i <= static::$itemsLimit; $i++) {
      $im->create(static::itemData($moduleName));
    }
  }

  static function itemData($strName) {
    $r = [];
    foreach ((new DdFields($strName))->getFieldsF() as $f) {
      if (DdTags::isTag($f['type'])) {
        $class = 'TestField'.ucfirst($f['type']);
        /* @var TestFieldDdTagsAbstract $test */
        $test = new $class();
        $test::$strName = $strName;
        $test::$tagFieldName = $f['name'];
        $test->createTags();
        $r[$f['name']] = $test->createData();
      } else {
        $r[$f['name']] = DdFieldCore::getType($f['type'])->sampleData();
      }
    }
    return $r;
  }

}