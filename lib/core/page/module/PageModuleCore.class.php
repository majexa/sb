<?php

/**
 * Иерархия модулей определяем иерархией классов PageModule*.
 * Например PageModuleCnd наследуется от PageModulePhotoalbum, который в свою очередь
 * является составным модулей 2-х разделов.
 */
class PageModuleCore {

  static function exists($module) {
    return class_exists(ClassCore::nameToClass('PageModule', self::getBaseName($module)));
  }

  static function getBaseName($module) {
    return Misc::removeSuffix('Slave', $module);
  }

  static function hasAncestor($module, $ancestorModule) {
    $module = Misc::removeSuffix('Slave', $module);
    return ClassCore::hasAncestor('PageModule'.ucfirst($module), 'PageModule'.ucfirst($ancestorModule));
  }

  static function action($module, $action, array $options = []) {
    if (($class = self::getClass($module, 'Pma')) === false) return false;
    $o = O::get($class, $options);
    if (!method_exists($o, $action)) return false;
    $o->$action();
  }

  /**
   * Возвращает существующий верхний класс из иерархии классов, определенной модулем
   *
   * @param string $module модуль, класс к которому нужно найти
   * @param string $prefix префикс искомого класса
   * @return bool
   */
  static function getClass($module, $prefix) {
    $classes = self::getAncestorClasses($module, $prefix);
    return isset($classes[0]) ? $classes[0] : false;
  }

  /**
   * Возвращает фиктивных предков класса, полученных из иерархии предков класса PageModule
   */
  static function getAncestorClasses($module, $prefix) {
    return array_values(array_filter(array_map(function ($v) use ($prefix) {
      return $prefix.$v;
    }, self::getAncestorNames($module, false)), function ($class) {
      return class_exists($class);
    }));
  }

  /**
   * Возвращает предков модуля (предков класса PageModule)
   */
  static function getAncestorNames($module, $firstLower = true) {
    $slave = Misc::hasSuffix('Slave', $module);
    if ($slave) $module = Misc::removeSuffix('Slave', $module);
    $class = 'PageModule'.ucfirst($module);
    if (!class_exists($class)) return [$firstLower ? $module : ucfirst($module)];
    $r = array_map(function ($v) use ($firstLower, $slave) {
      $r = str_replace('PageModule', '', $v).($slave ? 'Slave' : '');
      if (!$r) return '';
      return $firstLower ? lcfirst($r) : $r;
    }, ClassCore::getAncestorsByPrefix($class, 'PageModule'));
    return Arr::filterEmptiesR($r);
  }

  static function getInfo($module) {
    return PageModuleCore::exists($module) ? new PageModuleInfo($module) : false;
  }

  static function initPage(DbModelPages $page) {
    if (empty($page->r['settings']['itemTitle'])) $page->r['settings']['itemTitle'] = 'запись';
  }

  static function getDefaultSettings($module) {
    foreach (self::getAncestorNames($module) as $v) {
      if (($v = Config::getVar('pms/'.$v, true)) !== false) return $v;
    }
    return [];
  }

  static public function sflm($module) {
    if (!PageModuleCore::exists($module)) return false;
    return new PageModuleSflm(Sflm::frontendName(), $module);
  }

}
