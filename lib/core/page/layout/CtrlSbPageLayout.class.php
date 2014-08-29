<?php

class CtrlSbPageLayout extends CtrlSbAdmin {

  function action_json_edit() {
    return new Form([
      [
        'title' => 'Тип',
        'type' => 'imagedRadio',
        'options' => [
          '1'
        ]
      ]
    ]);
  }

}