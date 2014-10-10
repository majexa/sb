<?php

class CtrlSbUser extends CtrlCommon {

  protected function getParamActionN() {
    return 3;
  }

  protected $user;

  protected function init() {
    die2(DdTags::get('shop', 'category')->getTags());
    $this->user = DbModelCore::get('users', $this->req->param(2));
  }

  function action_default() {
    // $this->d[''] = (new DdItems('info'))->getItems();
    // (new DdItems('tracks'))->getItems();
  }

  function action_tracks() {
    $tracks = (new DdItems('shop'))->addF('user', $this->user['id'])->getItems();
    $r = [];
    foreach ($tracks as $v) {
      if (!isset($r[$v['album']['id']])) {
        $r[$v['album']['id']] = [];
        $r[$v['album']['id']]['album'] = $v['album'];
      }
    }
    // контроллеры
    // модели
    // группировка
  }

}