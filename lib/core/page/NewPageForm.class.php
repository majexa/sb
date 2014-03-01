<?php

class NewPageForm extends Form {

  protected $pageId;
  
  function __construct($pageId) {
    $this->pageId = $pageId;
    $this->options['submitTitle'] = 'Создать';
    parent::__construct(new Fields([
      [
        'title' => 'Название раздела',
        'name' => 'title',
        'required' => true
      ],
      [
        'title' => 'Имя страницы',
        'help' => 'будет использоваться для формирования адреса страницы',
        'name' => 'name'
      ],
      [
        'title' => 'Папка',
        'name' => 'folder',
        'type' => 'boolCheckbox'
      ],
      [
        'type' => 'pageController',
        'name' => 'controller'
      ]
    ]));
  }
  
  protected function _update(array $data) {
    $data['parentId'] = $this->pageId;
    DbModelCore::create('pages', $data);
  }
  
}