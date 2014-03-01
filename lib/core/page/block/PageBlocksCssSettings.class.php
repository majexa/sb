<?php

class PageBlocksCssSettings {
  
  static function get($pageId) {
    if (!($arr = Config::getVar('pageBlocks_'.$pageId, true)))
      $arr = Config::getVar('pageBlocksSettings');
    return Arr::filterEmptyStrings($arr);
  }
  
}
