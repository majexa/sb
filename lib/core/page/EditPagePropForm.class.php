<?php

class EditPagePropForm extends EditPagePropFormBase {

  function __construct($pageId, $god) {
    $this->god = $god;
  	parent::__construct($pageId);
    $this->options['filterEmpties'] = true;
    $this->addVisibilityCondition('linkSection', 'module', 'v == "link"');
  }
  
  protected function _getFields() {
    $fields = array_merge(parent::_getFields(), [
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
      ],
      [
        'title' => 'Сделать главной страницей',
        'name' => 'home',
        'type' => 'boolCheckbox'
      ]
    ]);
    if ($this->god) {
      $fields[] = [
        'title' => 'Модуль',
        'name' => 'module'
       ];
      $fields[] = [
        'type' => 'pageController',
        'name' => 'controller'
      ];
    } else {
      $fields[] = [
        'title' => 'Модуль',
        'name' => 'module',
        'type' => 'hidden'
      ];
    }
    return $fields;
  }

}
