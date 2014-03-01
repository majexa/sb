<?php

class EditPagePropFormBase extends Form {
  
  protected $pageId;
  
  function __construct($pageId) {
    $this->pageId = $pageId;
    parent::__construct(new Fields($this->_getFields()));
    $this->setElementsData(DbModelCore::get('pages', $this->pageId)->r);
  }
  
  protected function _getFields() {
    return [
      [
        'title' => 'Название раздела',
        'name' => 'title',
        'required' => true
      ],
      [
        'title' => 'Каталог',
        'name' => 'folder',
        'type' => 'boolCheckbox'
      ]
    ];
  }
  
  protected function _update(array $data) {
  	DbModelCore::update('pages', $this->pageId, $data);
  }
  
}
