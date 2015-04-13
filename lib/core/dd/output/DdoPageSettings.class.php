<?php

class DdoPageSettings extends DdoSettings {

  protected $page;

  function __construct(DbModelPages $page) {
    $this->page = $page;
    parent::__construct($this->page['strName']);
  }

  protected function getFile($prefix, $suffix = null) {
    foreach (PageModuleCore::getAncestorNames($this->page['module'], true, true) as $module) {
      $path = $prefix.($suffix ? '.'.$suffix : '');
      if (($file = O::get('PageModuleInfo', $module)->getFile($path)) !== false) {
        return $file;
      }
    }
    return false;
    //return parent::getKey($prefix, $suffix);
  }

  protected function getVar($prefix, $suffix = null) {
    if (($file = $this->getFile($prefix, $suffix)) !== false) {
      return require $file;
    }
    return Config::getVar($this->getKey($prefix, $suffix), true);
  }

  function delete() {
    //Dir::deleteFiles(PROJECT_PATH."/config/vars/ddo", '*.'.$this->pageModule.'.*');
  }

  function renameField($old, $new) {
    if (!empty($this->page['module'])) {
    }
    else {
      $prefixes = ['fieldOrder'];
      $prefixesSubvalues = ['itemsShow', 'outputMethod'];
      foreach ($prefixes as $prefix) {
        $key = "ddo/$prefix.{$this->page['strName']}";
        if (($file = ProjectConfig::hasSiteVar($key)) !== false) {
          ProjectConfig::updateVar($key, Arr::replaceKey(include $file, $old, $new));
        }
      }
      foreach ($prefixesSubvalues as $prefix) {
        $key = "ddo/$prefix.{$this->page['strName']}";
        if (($file = ProjectConfig::hasSiteVar($key)) !== false) {
          $r = include $file;
          foreach ($r as $k => $v) $r[$k] = Arr::replaceKey($v, $old, $new);
          ProjectConfig::updateVar($key, $r);
        }
      }
    }
  }

  function rename($newModule) {
    //SiteConfig::renameVar('ddo/fieldOrder', $this->pageModule, $newModule);
    //SiteConfig::renameVar('ddo/itemsShow', $this->pageModule, $newModule);
    //SiteConfig::renameVar('ddo/outputMethod', $this->pageModule, $newModule);
  }

}