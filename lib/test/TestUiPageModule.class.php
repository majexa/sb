<?php

abstract class TestUiPageModule extends TestUiSb {

  protected function casper(array $steps) {
    parent::casper($steps, [
      'captureFolder' => 'pageModules/'.$this->captureFolderName()
    ]);
  }

  protected function captureFolderName() {
    return lcfirst(Misc::removePrefix('TestPageModule', get_class($this)));
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