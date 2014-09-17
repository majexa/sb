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

  protected $list;

  function action_default() {
    if (isset($this->req->params[1]) and is_numeric($this->req->params[1])) {
      $this->list = false;
      $this->d['content'] = $this->ddo()->setItem($this->items()->getItem($this->req->params[1]))->els();
      $this->d['content'] .= Tt()->getTpl('msgs/default');
    }
    else {
      $this->list = true;
      $this->d['content'] = $this->ddo()->setItems($this->items()->getItems())->els();
    }
  }

}