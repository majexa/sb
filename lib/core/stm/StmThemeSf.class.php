<?php

/**
 * Base class for theme static files (js, css) generators
 */
abstract class StmThemeSf {

  /**
   * @var StmThemeData
   */
  public $data;

  function __construct(StmThemeData $data) {
    $this->data = $data;
    $this->init();
  }

  abstract protected function init();

  /**
   * @return StmMenuData
   */
  protected function getMenuData() {
    if (empty($this->data['menu'])) return false;
    return O::get('StmMenuData', $this->data->source, [
      'id'      => $this->data->id,
      'siteSet' => $this->data['siteSet'],
      'design'  => $this->data['design']
    ]);
  }

}