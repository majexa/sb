<?php

class PageBlockTypeForm extends Form {
  
  function __construct(array $options = []) {
    parent::__construct(new Fields([
      [
        'name' => 'type',
        'title' => 'Тип',
        'type' => 'select',
        'options' => PageBlockCore::getTypeOptions(),
        'required' => true
      ]
    ]), $options);
  }
  
}