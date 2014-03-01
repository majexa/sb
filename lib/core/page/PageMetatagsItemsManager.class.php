<?php

class PageMetatagsItemsManager extends DbItemsManager {
  
  public $pageId;

  function __construct($pageId) {
    $this->pageId = $pageId;
    $this->form = new PageMetatagsForm();
    $this->items = new PageMetatagsItems();
  }
  
  protected function replaceData(&$data) {
    $data['pageId'] = $this->pageId;
  }
  
}
