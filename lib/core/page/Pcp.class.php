<?php

abstract class Pcp {

  public $title;
  public $visible = true;
  public $editebleContent = false;
  
  function getProperties() {
    return [
      [
        'name' => 'mainTpl', 
        'title' => 'Главный шаблон', 
        'type' => 'text'
      ],
      [
        'name' => 'defaultAction', 
        'title' => 'Экшн по умолчанию', 
        'type' => 'select',
        'options' => DefaultAction::options()
      ]
    ];
  }
  
  function getAfterSaveDialogs(PageControllerSettingsForm $oF) {
    return false;
  }

}