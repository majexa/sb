<?php

class PageModules {

  public $items;
	
  function __construct() {
    $this->items = [];
    foreach (ClassCore::getDescendants('PageModule', 'PageModule') as $v) {
      $o = O::get($v['class']);
      $this->items[$v['name']] = [
        'title' => $o->title,
        'controller' => $o->controller,  
        'oid' => empty($o->oid) ? 9999 : $o->oid
      ];
    }
    $this->items = Arr::sortByOrderKey($this->items, 'oid');
  }

  function getItems() {
    return $this->items;
  }

}
