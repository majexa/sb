<?php

class NewModulePageFormBase extends Form {

  protected $pageId;
  protected $allowAllModules = true;

  function __construct($pageId) {
    $this->pageId = $pageId;
    $this->options['submitTitle'] = 'Создать';
    parent::__construct(new Fields($this->_getFields()));
  }

  protected function _getFields() {
    return [
      [
        'title'    => 'Тип раздела',
        'name'     => 'module',
        'type'     => 'select',
        'required' => true,
        'default'  => 'content',
        'options'  => array_merge(Html::defaultOption(), Arr::get((Misc::isGod() and $this->allowAllModules) ? (new PageModules())->getItems() : (new PageModulesAllowed())->getItems(), 'title', 'KEY'))
      ], [
        'title'    => 'Название раздела',
        'name'     => 'title',
        'required' => true
      ], [
        'title' => 'Папка',
        'name'  => 'folder',
        'type'  => 'boolCheckbox'
      ]
    ];
  }

  protected function _update(array $data) {
    $data['parentId'] = $this->pageId;
    Pmi::get($data['module'])->install($data);
  }

}