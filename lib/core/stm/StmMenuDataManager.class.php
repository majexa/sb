<?php

class StmMenuDataManager extends StmDataManager {

  static $requiredOptions = ['location', 'id'];
  
  protected function defineOptions() {
    return [
      'type' => 'menu',
      'subType' => 'menu'
    ];
  }
  
}
