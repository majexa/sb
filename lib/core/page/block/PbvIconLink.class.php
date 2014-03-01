<?php

class PbvIconLink extends PbvAbstract {
  
  static $cachable = true;

  function styles() {
    $s = getimagesize(UPLOAD_PATH.'/'.$this->pageBlock['settings']['image']);
    return [
      'background-image' => 'url('.UPLOAD_DIR.'/'.$this->pageBlock['settings']['image'].')',
      'padding-left' => ($s[0]+5).'px',
      'min-height' => $s[1].'px'
    ];
  }

  function _html() {
    return 
      '<h2><a href="'.$this->pageBlock['settings']['url'].'">'.
      $this->pageBlock['settings']['title'].'</a></h2>'.
      $this->pageBlock['settings']['text'];
  }

}