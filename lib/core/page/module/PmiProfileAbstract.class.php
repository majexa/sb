<?php

abstract class PmiProfileAbstract extends PmiDd {
  
  public $controller = 'myProfile';
  public $onMenu = false;
  public $hasSlices = false;
  
  protected $strType = 'static';

  protected function getSettings() {
    return array_merge(parent::getSettings(), [
      'smW' => 50,
      'smH' => 50,
      'mdW' => 180,
      'mdH' => 400,
    ]);
  }
  
}