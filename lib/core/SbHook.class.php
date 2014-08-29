<?php

class SbHook {

  static function paths($path, $pageModule = null) {
    $paths = Hook::paths($path);
    if ($pageModule and ($info = PageModuleCore::getInfo($pageModule)) !== false) {
      if (($file = $info->getFile('hooks/'.$path)) !== false) {
        $paths[] = $file;
      }
    }
    return $paths;
  }

}