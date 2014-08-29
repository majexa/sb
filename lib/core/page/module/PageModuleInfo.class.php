<?php

class PageModuleInfo {

  public $module, $folderPath, $folderWpath;

  static $folders = [];

  function __construct($module) {
    $this->module = $module;
    $this->initFolderPaths();
  }

  static function addFolder($path, $wPath) {
    self::$folders[] = [$path, $wPath];
  }

  protected function initFolderPaths() {
    foreach (PageModuleCore::getAncestorNames($this->module) as $_module) if ($this->_setFolderPaths($_module)) return;
    throw new Exception("No such module as '{$this->module}'");
  }

  function getFilePaths($filename) {
    foreach (PageModuleCore::getAncestorNames($this->module) as $module) {
      if (file_exists(CORE_PAGE_MODULES_PATH.'/'.$module.'/'.$filename)) {
        return [
          CORE_PAGE_MODULES_PATH.'/'.$module.'/'.$filename, CORE_PAGE_MODULES_DIR.'/'.$module.'/'.$filename
        ];
      }
      elseif (file_exists(SITE_PAGE_MODULES_PATH.'/'.$module.'/'.$filename)) {
        return [
          SITE_PAGE_MODULES_PATH.'/'.$module.'/'.$filename, SITE_PAGE_MODULES_DIR.'/'.$module.'/'.$filename
        ];
      }
    }
    return false;
  }

  protected function _setFolderPaths($module) {
    if (file_exists(CORE_PAGE_MODULES_PATH.'/'.$module)) {
      $this->folderPath = CORE_PAGE_MODULES_PATH.'/'.$module;
      $this->folderWpath = CORE_PAGE_MODULES_DIR.'/'.$module;
      return true;
    }
    elseif (file_exists(SITE_PAGE_MODULES_PATH.'/'.$module)) {
      $this->folderPath = SITE_PAGE_MODULES_PATH.'/'.$module;
      $this->folderWpath = SITE_PAGE_MODULES_DIR.'/'.$module;
      return true;
    }
    else {
      return false;
    }
  }

  function getFile($path) {
    return file_exists($this->folderPath.'/'.$path.'.php') ? $this->folderPath.'/'.$path.'.php' : false;
  }

  function getData($path) {
    if (($file = $this->getFile($path)) === false) return false;
    return include $file;
  }

}

PageModuleInfo::addFolder(CORE_PAGE_MODULES_PATH, CORE_PAGE_MODULES_DIR);