<?php

class FieldEStmMenuSelect extends FieldESelect {

  protected function defineOptions() {
    return [
      'options' => array_merge(['' => 'не определено'], Arr::get(StmCore::getMenuStructures(), 'title', 'KEY'))
    ];
  }

}