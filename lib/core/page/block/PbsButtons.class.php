<?php

class PbsButtons extends PbsAbstract {

  static $title = 'Кнопки';
  
  protected function initFields() {
    $this->fields = [
      [
        'title' => 'Конпки',
        'name' => 'buttons', 
        'type' => 'fieldSet', 
        'fields' => [
          [
            'title' => 'Текст',
            'name' => 'title'
          ], 
          [
            'title' => 'Ссылки',
            'name' => 'link'
          ]
        ]
      ]
    ];
  }

}