<?php

class SbHook {

  static function paths($path, $pageModule = null) {
    $paths = Hook::paths($path);
    if ($pageModule) {
      foreach (array_reverse(PageModuleCore::getAncestorNames($pageModule, true, true)) as $_pageModule) {
        if (($info = PageModuleCore::getInfo($_pageModule)) !== false) {
          if (($file = $info->getFile('hooks/'.$path)) !== false) {
            $paths[] = $file;
          }
        }
      }
    }
    return $paths;
  }

}