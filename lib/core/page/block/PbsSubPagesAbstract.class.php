<?php

abstract class PbsSubPagesAbstract extends PbsAbstract {

  protected function initFields() {
    $this->fields[] = [
      'title' => 'Количество открытых уровней',
      'name' => 'openDepth',
      'type' => 'select',
      'default' => 2,
      'required' => true,
      'options' => [1, 2, 3, 4, 5, 6]
    ];
  }

}
