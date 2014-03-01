<?php

class PageBlockTypeSimpleForm extends Form {

  function __construct(array $options = []) {
    parent::__construct(new Fields([
      [
        'name' => 'type',
        'title' => 'Тип',
        'type' => 'imagedRadio',
        'default' => 'text',
        'options' => Arr::filterByKeys(PageBlockCore::getTypeOptions(), [
          'text',
          'textAndImage',
          'auth',
          'subPages',
          'tags',
          //'items',
        ]),
        'required' => true
      ]
    ]), $options);
  }

}