<?php

/**
 * Существует 4 типа контроллеров:
 * 1) Динамический контроллер раздела
 * 2) Статический контроллер раздела
 * 3) Контроллер, использующий модель раздела. Модель получается по ID
 * 4) Контроллер, не использующий модель раздела
 */
class SbRouter extends DefaultRouter {

  protected function getFrontendName__() {
    return 'sb';
    //return Misc::isAdmin() ? 'sbAdmin' : 'sb';
  }

  function _getController() {
    if (isset($this->req->params[1]) and $this->req->params[0] == 'sbc') {
      $class = 'CtrlSb'.ucfirst($this->req->params[1]);
      return new $class($this);
    };
    if (($object = $this->getPageOrController()) === false) die2('FALSE what\'s now');
    if ($object instanceof DbModelPages) return $this->getPageController($object);
    return $object;
  }

  /**
   * @return DbModelPages|CtrlPageV
   */
  protected function getPageOrController() {
    if (isset($this->req->params[0]) and is_numeric($this->req->params[0]) and ($page = DbModelCore::get('pages', $this->req->params[0])) !== false) {
      if (empty($page['path'])) return false;
      $pathParams2 = (count($this->req->params) > 1) ? '/'.implode('/', array_splice($this->req->params, 1, count($this->req->params))) : '';
      header('Location: /'.$page['path'].$pathParams2.($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : ''));
      // реализовать этот кусок для базового роутера
      return false;
    }
    else {
      if (!isset($this->req->params[0]) or $this->req->params[0] == 'index.php') {
        return PageControllersCore::getStaticCtrl('default', $this);
      }
      else {
        $ctrlName = $this->req->param(0);
        if (($routes = Config::getVar('routes')) and isset($routes[$ctrlName])) $ctrlName = $routes[$ctrlName];
        if (PageControllersCore::staticCtrlExists($ctrlName)) {
          return PageControllersCore::getStaticCtrl($ctrlName, $this);
        }
        elseif (($page = DbModelCore::get('pages', $ctrlName, 'path')) !== false) { // by path
          return $page;
        }
        elseif (($page = DbModelCore::get('pages', $ctrlName, 'name')) !== false) { // by name
          return $page;
        }
      }
    }
  }

  protected function getPageController(DbModelPages $page) {
    if (!$page['active']) return new Ctrl404($this, new Exception('Page does not exists'));
    elseif (!empty($page['link'])) {
      redirect($page['link']);
      // реализовать этот кусок для базового роутера
      die();
    }
    elseif (!empty($page['controller'])) return PageControllersCore::getController($this, $page);
    throw new Exception('page id='.$page['id'].' has no controller');
  }

}