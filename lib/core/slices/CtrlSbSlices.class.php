<?php

class SliceForm extends Form {

  protected $sliceIdl;

  function __construct($sliceId) {
    $this->sliceId = $sliceId;
    parent::__construct([
      [
        'title' => 'Текст',
        'type' => 'wisiwigSimple',
        'name' => 'text'
      ]
    ]);
  }

  protected function _update(array $data) {
    DbModelCore::update('slices', $this->sliceId, $data);
  }

}

class CtrlSbSlices extends CtrlSbAdmin {

  function action_json_edit() {
    return $this->jsonFormActionUpdate(new SliceForm($this->req->param(2)));
  }

}