<?php

class PageModulePageDd extends PageModulePage {

  function delete() {
    (new DdStructuresManager)->deleteByName($this->page['strName']);
    parent::delete();
  }

}