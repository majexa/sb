<?php

class PageControllerSettingsForm extends DdForm {

  /**
   * @var DbModelPages
   */
  protected $page;

  function __construct(DbModelPages $page) {
    $this->page = $page;
    parent::__construct(
      new PageControllerSettingsFields($page['controller']),
      $page['strName'],
      [
        'filterEmpties' => true
      ]
    );
    if (!empty($this->page['initSettings']))
      $this->setElementsData($this->page['initSettings']);
  }
  
  protected function _update(array $settings) {
    DbModelCore::update('pages', $this->page['id'], ['settings' => $settings]);
  }
  
}
