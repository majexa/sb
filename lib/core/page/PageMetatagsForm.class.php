<?php

class PageMetatagsForm extends Form {
  
  function __construct() {
    parent::__construct(new PageMetatagsFields());
  }
  
}
