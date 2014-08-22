<?php

abstract class CtrlPageStatic extends CtrlPage {

  static protected function getStaticName() {
    return ClassCore::classToName('CtrlPageStatic', get_called_class());
  }

  protected function getName() {
    return ClassCore::classToName('CtrlPageStatic', get_class($this));
  }

  static function getPage() {
    $r = static::page();
    $r['module'] = $r['path'] = self::getStaticName();
    $r['active'] = true;
    if (empty($r['id'])) $r['id'] = -(round(hexdec(md5($r['module'])) / 10000000000000000000000000000000000));
    return new DbModelVirtual($r);
  }

  static protected function page() {
    return [
      'title' => 'dummy'
    ];
  }

  function dispatch() {
    $this->setPage(static::getPage());
    parent::dispatch();
  }

}