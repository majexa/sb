<?php

class NewModulePageForm extends NewModulePageFormBase {

  protected $allowAllModules = true;

  protected function init() {
    $this->addVisibilityCondition('typeSection', 'folderPageType', 'v != "empty"');
    $this->addVisibilityCondition('linkSection', 'module', 'v == "link"');
  }

  protected function _getFields() {
    $fields = parent::_getFields();
    return array_merge($fields, [
      [
        'name' => 'typeSection',
        'type' => 'headerVisibilityCondition'
      ],
      [
        'name' => 'linkSection',
        'type' => 'headerVisibilityCondition'
      ],
      [
        'title' => 'Ссылка',
        'name' => 'link',
        'type' => 'pageLink',
      ],
      [
        'title' => 'Дополнительные параметры',
        'type' => 'headerToggle'
      ],
      [
        'title' => 'Имя страницы',
        'help' => 'будет использоваться для формирования адреса страницы',
        'name' => 'name'
      ],
      [
        'title' => 'Полный заголовок раздела',
        'help' => 'используется, если заголовок раздела на странице боле длинный, чем в меню',
        'name' => 'fullTitle'
      ]
    ]);
  }

}