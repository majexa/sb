<?php

class PageControllersCore {

  /**
   * Возвращает массив названий PageController'ов:
   * ['moduleControllerName' => 'moduleControllerTitle']
   *
   * @param bool $onlyVisible
   * @return array
   */
  static function getTitles($onlyVisible = true) {
    $titles = [];
    foreach (ClassCore::getNames('Pcp') as $name) {
      $o = self::getControllerProp($name, true);
      if ($onlyVisible and !$o->visible) continue;
      $titles[$name] = $o->title;
    }
    return $titles;
  }

  /**
   * @param string $name Имя контроллера
   * @param bool $strict
   * @return Pcp|bool
   */
  static function getControllerProp($name = '', $strict = false) {
    $class = ClassCore::nameToClass('Pcp', $name);
    return $strict ? O::get($class) : (class_exists($class) ? O::get($class) : false);
  }

  /**
   * Возвращает контроллер Раздела
   *
   * @param Router $router
   * @param DbModelPages $page
   * @param array $options
   * @return mixed
   */
  static function getController(Router $router, DbModelPages $page, array $options = []) {
    $class = ClassCore::nameToClass('CtrlPage', $page['controller']);
    ClassCore::checkExistance($class);
    $ctrl = new $class($router, $options); // Необходимо получать объект напрямую без кэширования
    $ctrl->setPage($page);
    return $ctrl;
  }

  /**
   * Произоводит преобразование массива настроек в зависимости от действий
   * прописаных в классе Pcsa* соответствующего плагина
   *
   * @param DbModelPages $page
   * @param $initSettings
   * @return mixed
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

  static function getStaticCtrl($ctrlName, Router $router) {
    return O::get(ClassCore::nameToClass('CtrlPageStatic', $ctrlName), $router);
  }

  static function staticCtrlExists($ctrlName) {
    return class_exists(ClassCore::nameToClass('CtrlPageStatic', $ctrlName));
  }

  static function getStaticCtrlClass($ctrlName) {
    return ClassCore::nameToClass('CtrlPageStatic', $ctrlName);
  }

  static function getStaticCtrlPageModels() {
    $r = [];
    foreach (ClassCore::getClassesByPrefix('CtrlPageStatic', true) as $class) {
      /* @var $class CtrlPageStatic */
      $page = $class::getPage();
      $r[$page['id']] = $page;
    }
    return $r;
  }

  static function getPageModel($id) {
    $pages = self::getStaticCtrlPageModels();
    if (isset($pages[$id])) return $pages[$id];
    return Misc::checkEmpty(DbModelCore::get('pages', $id));
  }

  static protected $paths = [];

  /**
   * Возвращает путь до первой найденной страницы с указанным модулем.
   * Если у вас существует 2 страницы регастрации, то Tt()->getControllerPath('userReg')
   * вернёт путь до страницы с меньшим ID
   *
   * @param $controllerName
   * @param bool $quietly
   * @return mixed
   * @throws Exception
   */
  static function getControllerPath($controllerName, $quietly = false) {
    if (($page = PageControllersCore::getStaticCtrlPageModel($controllerName)) !== false) return $page->r['path'];
    if (isset(self::$paths[$controllerName])) return self::$paths[$controllerName];
    if (($page = DbModelCore::get('pages', $controllerName, 'controller')) !== false) return self::$paths[$controllerName] = $page->r['path'];
    if (!$quietly) throw new Exception("Page with controller '$controllerName' not found");
    return $controllerName;
  }

}
