<?php

class PageModuleBehaviorComments extends PageModuleBehaviorAbstract {
  
  function action($pageId, $node) {
    $oP = new PrivilegesManager();
    $oP->create(ALL_USERS_ID, $pageId, 'create');
    $oP->create(ALL_USERS_ID, $pageId, 'sub_create');
  }

}

