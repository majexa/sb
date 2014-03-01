<?php

class FieldEOrderUsers extends FieldESelect {

  protected function defineOptions() {
    $fields = [
      'dateCreate' => [
        'name' => 'dateCreate',
        'title' => LANG_CREATION_DATE
      ],
      'dateUpdate' => [
        'name' => 'dateUpdate',
        'title' => LANG_UPDATE_DATE
      ]
    ];
    $options['options'] = ['' => '— '.LANG_NOTHING_SELECTED.' —'];
    foreach ($fields as $v) {
      $options['options'][$v['name']] = $v['title'];
      $options['options'][$v['name'].' DESC'] = $v['title'].' ['.LANG_REVERSE_ORDER.']';
    }
    return $options;
  }

}
