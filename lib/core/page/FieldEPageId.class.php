<?php

class FieldEPageId extends FieldEHiddenWithRow {

  protected function defineOptions() {
    return array_merge(parent::defineOptions(), [
      'useTypeJs' => true,
    ]);
  }

}