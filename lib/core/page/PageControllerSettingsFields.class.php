<?php

class PageControllerSettingsFields extends Fields {
  
  function __construct($controller) {
    $fields = [];
    foreach (PageControllersCore::getPropObj($controller)->getProperties() as $k => $v) {
      $fields["page[settings][$k]"] = $v;
    }
    parent::__construct($fields);
  }
  
}