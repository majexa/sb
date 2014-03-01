<?php

class PbvIconLinks extends PbvAbstract {
  
  static $cachable = false;

  function _html() {
    $table = [];
    foreach ($this->pageBlock['settings']['items'] as $v) {
      $table[] = [
        '<img src="'.UPLOAD_DIR.'/'.$v['image'].'" />',
        '<a href="'.$v['url'].'">'.$v['text'].'</a>',
      ];
    }
    return
      ($this->pageBlock['settings']['title'] ?
        '<h2>'.$this->pageBlock['settings']['title'].'</h2>' : '').
      Tt()->getTpl('common/table', $table);
  }

}