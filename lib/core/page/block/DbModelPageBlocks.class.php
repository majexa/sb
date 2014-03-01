<?php

class DbModelPageBlocks extends DbModel {

  function updateGlobal($flag, $ownPageId = 0) {
    if ($flag) {
      $this->r['global'] = 1;
      $this->r['ownPageId'] = 0;
    } else {
      $this->r['global'] = 0;
      $this->r['ownPageId'] = $ownPageId;
    }
    $this->save();
  }

  static $serializeble = [
    'settings'
  ];

  static function afterCreateUpdate($id) {
    PageBlockCore::cc($id);
  }

}
