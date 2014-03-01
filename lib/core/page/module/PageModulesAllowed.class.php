<?php

class PageModulesAllowed extends PageModules {

  function __construct() {
    parent::__construct();
    $this->items = Arr::filterByKeys($this->items,
      Config::getVarVar('adminPriv', 'allowedPageModules'));
  }

}
