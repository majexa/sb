<?php

class PbvUsersOnline extends PbvAbstract {
  
  static $cachable = false;
  
  function _html() {
    return Tt()->getTpl('common/usersOnline', $this->pageBlock['settings']);
  }
  
}