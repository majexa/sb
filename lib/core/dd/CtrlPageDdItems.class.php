<?php

class CtrlPageDdItems extends CtrlPageDd {
  use DdCrudParamFilterCtrl, PermissionCtrl;

  protected function getStrName() {
    return $this->page['strName'];
  }

  protected function getBasePath() {
    return '/'.$this->req->param(0);
  }

  protected $list;

  protected function beforeAction() {
    parent::beforeAction();
    $this->list = !(isset($this->req->params[1]) and is_numeric($this->req->params[1]));
    if ($this->hasOutput and $this->list and $this->permission()->allow('new')) {
      $this->d['topBtns'][] = [
        'title' => 'Создать',
        'class' => 'new',
        'data'  => [
          'url' => $this->getBasePath().'/json_new'
        ]
      ];
    }
  }

  protected function _getIm() {
    return new DdItemsManagerPage($this->page, $this->items(), $this->objectProcess(new DdForm(new DdFields($this->getStrName()), $this->getStrName()), 'form'));
  }

  /**
   * @return DdoPage
   */
  protected function ddo() {
    return $this->objectProcess(DdoPageModule::factory($this->page, $this->getDdLayout()), 'ddo');
  }

  protected function id() {
    return $this->req->param(1);
  }

  protected function getDdLayout() {
    return $this->list ? 'siteItems' : 'siteItem';
  }

  function action_default() {
    if ($this->list) {
      $this->d['content'] = $this->ddo()->setItems($this->items()->getItems())->els();
    }
    else {
      $this->d['content'] = $this->ddo()->setItem($this->items()->getItem($this->req->params[1]))->els();
      $this->d['content'] .= Tt()->getTpl('msgs/default');
    }
  }

}