<?php

class CtrlCommonStoreCart extends CtrlMapper {
  
  function getMappingObject() {
    return StoreCart::get();
  }

  //protected function sflmStore() {}
  
}
