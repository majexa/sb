<?php

abstract class PbsPage extends PbsAbstract {

  protected function initPreFields() {
    $this->preFields[] = [
      'title' => 'Раздел',
      'name' => 'pageId',
      'type' => 'pageId',
      'required' => true
    ];
  }

}
