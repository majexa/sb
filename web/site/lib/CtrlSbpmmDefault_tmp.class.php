<?php

class CtrlSbpmmDefault_tmp extends CtrlDefault {

  protected function getParamActionN() {
    return 0;
  }

  function action_default() {
    Sflm::frontend('js')->addClass('Ngn.Btn');
    $this->d['scenarios'] = array_map(function($v) {
      return [
        'link' => '/'.$v,
        'title' => $v,
        'id' => $v,
      ];
    }, Dir::getFiles(DATA_PATH.'/scenarios'));
  }

  function action_json_open() {
    $this->json['text'] = file_get_contents(DATA_PATH.'/scenarios/'.$this->req->param(1));
  }

}