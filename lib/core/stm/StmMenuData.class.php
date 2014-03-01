<?php

class StmMenuData extends StmData {

  /**
   * @var StmMenuDataSource
   */
  public $source;
  
  public $name = 'menu';
  
  static $requiredOptions = ['id'];
  
  public $menuType;

  function __construct(StmDataSource $source, array $options) {
    parent::__construct($source, $options);
  }

  function init() {
    parent::init();
    $theme = new StmThemeData($this->source, $this->options);
    Arr::checkEmpty($theme->data['data'], 'menu');
    $this->menuType = $theme->data['data']['menu'];
    $this->data['siteSet'] = $theme->data['siteSet'];
    $this->data['design'] = $theme->data['design'];
    return $this;
  }

  function getStructure() {
    return StmCore::getMenuStructure($this->menuType);
  }

}
