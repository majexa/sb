<?php

class PageBlockSettingsForm extends Form {

  function __construct(PageBlockTBase $oPB) {
    parent::__construct(new Fields($oPB->getFields()));
  }
  
}