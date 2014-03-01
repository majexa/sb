<?php

class PageControllersCore {

  static $propObjs;

  static function exists($name) {
    return DbModelCore::get('pages', $name, 'controller') !== false;
  }

  /**
   * Возвращает массив названий PageController'ов
   *
   * @return array  array('moduleControllerName' => 'moduleControllerTitle')
   */
  static function getTitles($onlyVisible = true) {
    $titles = [];
    foreach (ClassCore::getNames('Pcp') as $name) {
      $o = self::getPropObj($name, true);
      if ($onlyVisible and !$o->visible) continue;
      $titles[$name] = $o->title;
    }
    return $titles;
  }

  static function getProperties($name) {
    self::getPropObj($name, true)->getProperties();
  }

  static function getTitle($name) {
    return self::getPropObj($name)->getTitle();
  }

  static function isEditebleContent($name) {
    if (($o = self::getPropObj($name)) !== false) return $o->editebleContent;
    return false;
  }

  /**
   * @param  string  Имя контроллер
   * @return Pcp
   */
  static function getPropObj($name = '', $strict = false) {
    $class = ClassCore::nameToClass('Pcp', $name);
    return $strict ? O::get($class) : (class_exists($class) ? O::get($class) : false);
  }

  /**
   * Возвращает контроллер Раздела
   *
   * @param   array   Массив с данными Раздела
   */

  /**
   * Возвращает контроллер Раздела
   *
   * @param   array     Массив с данными Раздела
   * @return  CtrlPage  Возвращает объект контрллера или FALSE в случае его отсутсвия
   */
  static function getController(Router $oD, DbModelPages $page, array $options = []) {
    $class = ClassCore::nameToClass('CtrlPage', $page['controller']);
    ClassCore::checkExistance($class);
    $ctrl = new $class($oD, $options); // Необходимо получать объект напрямую без кэширования
    $ctrl->setPage($page);
    return $ctrl;
  }

  /**
   * Произоводит преобразование массива настроек в зависимости от действий
   * прописаных в классе Pcsa* соответствующего плагина
   *
   * @param   string  Имя плагина
   * @param   array   Массив исходных настроек
   * @return  array   Массив конечных настроек
   */
  static function settingsAction(DbModelPages $page, $initSettings) {
    if (empty($page['controller'])) return $initSettings;
    $class = 'Pcsa'.ucfirst($page['controller']);
    if (!class_exists($class)) return $initSettings;
    return O::get($class, $page)->action($initSettings);
  }

  static function hasAncestor($controller, $ancestorController) {
    return ClassCore::hasAncestor('CtrlPage'.ucfirst($controller), 'CtrlPage'.ucfirst($ancestorController));
  }

  static function isMaster($controller) {
    return self::hasAncestor($controller, 'ddItemsMaster');
  }

  static function getDefaultSettings($controller) {
    $class = ClassCore::nameToClass('Pcp', $controller);
    if (!class_exists($class)) return [];
    foreach (ClassCore::getAncestorNames($class, 'Pcp') as $name) {
      if (($v = Config::getVar('pcs.'.$name, true)) !== false) return $v;
    }
    return [];
  }

  static function getVirtualCtrl($controller, Router $router) {
    return O::get(ClassCore::nameToClass('CtrlPageV', $controller), $router)->setPage(self::getVirtualCtrlPageModel($controller));
  }

  static function virtualCtrlExists($controller) {
    return class_exists(ClassCore::nameToClass('CtrlPageV', $controller));
  }

  static function getVirtualCtrlClass($controller) {
    return ClassCore::nameToClass('CtrlPageV', $controller);
  }

  static function getVirtualCtrlPageModel($controller) {
    $class = self::getVirtualCtrlClass($controller);
    if (!class_exists($class)) return false;
    $page = method_exists($class, 'getVirtualPage') ? $class::getVirtualPage() : ['title' => 'empty'];
    $virtualPageModel = new DbModelVirtual($page);
    $virtualPageModel->r['path'] = $controller;
    $virtualPageModel->r['module'] = $controller;
    $virtualPageModel->r['active'] = true;
    $virtualPageModel->r['id'] = 9999;
    return $virtualPageModel;
  }

  static protected $paths = [];

  /**
   * Возвращает путь до первой найденной страницы с указанным модулем.
   * Если у вас существует 2 страницы регастрации, то Tt()->getControllerPath('userReg')
   * вернёт путь до страницы с меньшим ID.
   *
   * @param   string  Имя модуля
   * @return  string  Путь доя страницы
   */
  static function getControllerPath($controller, $quietly = false) {
    if (($page = PageControllersCore::getVirtualCtrlPageModel($controller)) !== false) return $page->r['path'];
    if (isset(self::$paths[$controller])) return self::$paths[$controller];
    if (($page = DbModelCore::get('pages', $controller, 'controller')) !== false) return self::$paths[$controller] = $page->r['path'];

    //if (!$quietly) throw new Exception("Page with controller '$controller' not found");
    return $controller;
  }

  static function isProfiles($controller) {
    return $controller == 'profiles';
  }

}