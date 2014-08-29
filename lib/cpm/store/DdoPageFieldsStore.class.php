<?php

class DdoPageFieldsStore extends DdoPageFields {

  protected function virtualFields() {
    return array_merge(parent::virtualFields(), [
      'buyBtn' => [
        'name'         => 'buyBtn',
        'oid'          => 200,
        'title'        => 'Купить',
        'type'         => 'btn',
      ],
    ]);
  }

}