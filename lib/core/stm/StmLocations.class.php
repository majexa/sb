<?php

class StmLocations {
  
  static $locations;
  
  static function init() {
    self::$locations = [
      'ngn' => [
        'canEdit' => false,
        'title' => 'Предустановленные',
        'themeFolder' => STM_DESIGN_PATH,
        'menuFolder' => STM_MENU_PATH
      ],
      'site' => [
        'canEdit' => true,
        'title' => 'Проект',
        'themeFolder' => STM_DATA_PATH.'/design',
        'menuFolder' => STM_DATA_PATH.'/menu'
      ]
    ];  	
  }

  static function getThemeFolders() {
    return Arr::get(self::$locations, 'themeFolder', 'KEY');
  }
  
  static function getMenuFolders() {
    return Arr::get(self::$locations, 'menuFolder', 'KEY');
  }
  
} StmLocations::init();
