<?php

class CtrlPageDdItems extends CtrlPageDd {
  use DdCrudParamFilterCtrl, PermissionCtrl;

  protected function getStrName() {
    return $this->page['strName'];
  }

  protected function getBasePath() {
    return '/'.$this->req->param(0);
  }

  protected function beforeAction() {
    parent::beforeAction();
    if ($this->hasOutput and $this->permission()->allow('new')) {
      $this->d['topBtns'] = [
        [
          'title' => 'Создать',
          'class' => 'new',
          'data'  => [
            'url' => $this->getBasePath().'/json_new'
          ]
        ]
      ];
    }
  }

  protected function id() {
    return $this->req->param(1);
  }

  function action_default() {
    $this->d['content'] = $this->ddo()->setItems($this->items()->getItems())->els();
  }

}