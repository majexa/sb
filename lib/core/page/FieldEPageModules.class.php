<?php

class FieldEPageModules extends FieldESelect {

  protected function defineOptions() {
    return [
      'options' => array_merge(['' => '—'], Arr::get(O::get('PageModules')->getItems(), 'title', 'KEY'))
    ];
  }

}