<?php

abstract class CtrlPageDdStatic extends CtrlPageDd {
  
  protected $defaultAction = 'showItem';
  
  public $adminTplFolder = 'item';
  
  protected $staticItemData;
  
  protected function init() {
    parent::init();
    $this->initItemsManager();
    $this->initStaticItemData();
    $this->d['ddType'] = 'ddStatic'; // Необходимо для шаблона модераторского-меню
  }
  
  function initStaticItemData() {
    /* @var $oDdItems DdItemsPage */
    $oDdItems = O::get('DdItemsPage', $this->page['id']);
    $this->staticItemData = $oDdItems->getItemByField('static_id', $this->im->static_id);
    $this->itemId = $this->staticItemData['id'];
  }
  
  function action_showItem() {
    if (!$this->staticItemData) {
      $this->error404('Содержание страницы не заполнено');
      return;
    }
    $this->d['tpl'] = 'item';
    $this->d['item'] = $this->staticItemData;
  }
  
  function action_new() {
    if (!empty($this->staticItemData)) {
      // Перебрасываем на страницу редактирования, если запись уже существует
      $this->redirect($this->tt->getPath().'?a=edit');
      return false;
    }
    parent::action_new();
  }  
  
  function action_edit() {
    // Запрещаем редактирование, если данные для записи ещё не существуют
    if (empty($this->staticItemData)) {
      $this->redirect($this->tt->getPath().'?a=new');
      return;
    }
    parent::action_edit();
  }
  
  protected function _initAllowedActions() {
    parent::_initAllowedActions();
    Arr::dropArr($this->allowedActions, ['activate', 'deactivate', 'delete']);
  }
  
  protected function getItemsManagerOptions() {
    return ['staticId' => $this->getStaticId()];
  }
  
  abstract protected function getStaticId();
  
}
