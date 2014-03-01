<?php

class PbsIconLinks extends PbsAbstract {

  static $title = 'Иконки с ссылками';

  protected function initFields() {
    $this->fields[] = [
      'name' => 'items',
      'type' => 'fieldSet',
      'fields' => [
        [
          'title' => 'Ссылка',
          'name' => 'url',
          'type' => 'pageLink',
          'required' => true
        ],
        [
          'title' => 'Изображение',
          'name' => 'image',
          'type' => 'image',
          'required' => true
        ],
        [
          'title' => 'Текст',
          'name' => 'text',
          'type' => 'textarea',
        ]
      ]
    ];
  }

}