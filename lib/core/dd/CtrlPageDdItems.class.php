<?php

class CtrlPageDdItems extends CtrlPage {
  use DdCrudCtrl, PermissionCtrl;

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

  function action_default() {
    $this->d['content'] = $this->ddo()->setItems($this->items()->getItems())->els();
  }

}