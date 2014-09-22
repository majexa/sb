<?php

class PageModuleSflmJsClassPaths extends SflmJsClassPaths {

  protected $module;

  function __construct($module) {
    $this->module = $module;
    if (($this->r = SflmCache::c()->load('jsClassPaths'.$module))) return;
    $this->init();
  }

  protected function files() {
    $files = parent::files();
    foreach (array_reverse(PageModuleCore::getAncestorNames($this->module, true, true)) as $module) {
      $files = array_merge($files, Dir::getFilesR((new PageModuleInfo($module))->folderPath, '[A-Z]*.js'));
    }
    return $files;
  }

}