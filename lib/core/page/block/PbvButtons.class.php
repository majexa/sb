<?php

class PbvButtons extends PbvAbstract {
  
  function _html() {
    $html = '';
    foreach ($this->pageBlock['settings']['buttons'] as $v) {
      $html .= '<div><a href="'.$v['link'].'" class="bbtn"><span><span>'.$v['title'].'</span></span><i></i></a><div class="clear"><!-- --></div></div>';
    }
    return $html;
  }
  
}