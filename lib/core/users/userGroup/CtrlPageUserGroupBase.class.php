<?php

class CtrlPageUserGroupBase extends CtrlPage {

  static function getVirtualPage() {
    return [
      'title' => 'Группа'
    ];
  }

  protected function init() {
    parent::init();
    if (!$this->userGroup) throw new EmptyException('$this->userGroup');
  }

}