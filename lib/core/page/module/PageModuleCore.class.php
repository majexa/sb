<?php

/**
 * Иерархия модулей определяем иерархией классов Pmi*.
 * Например PmiCnd наследуется от PmiPhotoalbum, который в свою очередь
 * является составным модулей 2-х разделов.
 */
class PageModuleCore {

  /**
   * Если модуль не имеет Pmi - значит он виртуальный
   *
   * @param string $module
   */
  static function isVirtual($module) {
    return !class_exists(ClassCore::nameToClass('Pmi', self::getBaseName($module)));
  }

  static function getBaseName($module) {
    return Misc::removeSuffix('Slave', $module);
  }

  static function exists($module) {
    return !self::isVirtual($module);
  }


  /**
   * Возвращает предков модуля (предков класса Pmi)
   */
  static function getAncestorNames($module, $firstLower = true) {
    $slave = Misc::hasSuffix('Slave', $module);
    if ($slave) $module = Misc::removeSuffix('Slave', $module);
    $class = 'Pmi'.ucfirst($module);
    if (!class_exists($class)) return [$firstLower ? $module : ucfirst($module)];
    $r = array_map(function($v) use ($firstLower, $slave) {
        $r = str_replace('Pmi', '', $v).($slave ? 'Slave' : '');
        if (!$r) return '';
        return $firstLower ? lcfirst($r) : $r;
      }, ClassCore::getAncestorsByPrefix($class, 'Pmi'));
    return Arr::filterEmptiesR($r);
  }

  /**
   * Возвращает фиктивных предков класса, полученных из иерархии предков класса Pmi
   */
  static function getAncestorClasses($module, $prefix) {
    return array_values(array_filter(array_map(function($v) use ($prefix) {
        return $prefix.$v;
      }, self::getAncestorNames($module, false)), function($class) {
      return class_exists($class);
    }));
  }

  static function hasAncestor($module, $ancestorModule) {
    $module = Misc::removeSuffix('Slave', $module);
    return ClassCore::hasAncestor('Pmi'.ucfirst($module), 'Pmi'.ucfirst($ancestorModule));
  }

  /**
   * Возвращает существующий верхний класс из иерархии классов, определенной модулем
   * @param  string  модуль, класс к которому нужно найти
   * @param  string  префикс искомого класса
   */
  static function getClass($module, $prefix) {
    $classes = self::getAncestorClasses($module, $prefix);
    return isset($classes[0]) ? $classes[0] : false;
  }

  static function action($module, $action, array $options = []) {
    if (($class = self::getClass($module, 'Pma')) === false) return;
    $o = O::get($class, $options);
    if (!method_exists($o, $action)) return false;
    $o->$action();
  }

  static function getTitle(DbModelPages $page) {
    if (!empty($page['module']) and ($pmi = Pmi::take($page['module'])) !== false) {
      return $pmi->title;
    }
    else {
      return PageControllersCore::getControllerProp($page['controller'])->title;
    }
  }

  static function sf($name, $module) {
    if (empty($module)) return '';
    if (PageModuleCore::isVirtual($module)) return "<!-- Module '$module' is VIRTUAL -->\n";
    return (new PageModuleSFLM($name, $module))->html();
  }

  static function inlineJs(array $d) {
    if (empty($d['page']['module'])) return;
    if (self::isVirtual($d['page']['module'])) return;
    if (($paths = O::get('PageModuleInfo', $d['page']['module'])->getFilePaths('inlineJs.php')) === false) return;
    print "\n<!-- inline JS for module '{$d['page']['module']}' -->\n";
    include $paths[0];
  }

  static function getInfo($module) {
    return PageModuleCore::isVirtual($module) ? false : new PageModuleInfo($module);
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

}
