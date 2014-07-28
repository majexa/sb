<?php

class SbRouter extends DefaultRouter {

  public $page;

  function dispatch() {
    //Sflm::frontend('css')->addLib('sb');
    //Sflm::frontend('js')->addLib('sb');
    //$this->checkAuthMode();
    $this->initPage();
    $this->initPageHtmlCache();
    if (!$this->cacheHtml) parent::dispatch();
    return $this;
  }

  private $showAuthPage = false;

  /**
   * Функция проверяет текущий авторизационный режим. Если authMode = 'denied'
   */
  protected function checkAuthMode() {
    if (ACCESS_MODE == 'all') return;
    if (!Auth::get('id')) $this->showAuthPage = true;
  }

  protected $cacheEnable = false, $cacheHtml, $cacheKey;

  protected function initPageHtmlCache() {
    if (!$this->cacheEnable) return;
    $this->cacheKey = str_replace('.', '_', str_replace('-', '_', 'htmlPage'.implode('', $this->req->params)));
    $this->cache = FileCache::c();
    $this->cacheHtml = $this->cache->load($this->cacheKey);
  }

  function _getController() {
    if (isset($this->req->params[0])) {
      if (isset($this->req->params[1]) and $this->req->params[0] == 'sbc') {
        $class = 'CtrlSb'.ucfirst($this->req->params[1]);
        return new $class($this);
      }
      $class = 'Ctrl'.ucfirst($this->req->params[0]);
      if (class_exists($class)) return new $class($this);
    }
    if ($this->showAuthPage) return new CtrlPageAuth($this);
    if ($this->virtualCtrl !== false) return PageControllersCore::getVirtualCtrl($this->virtualCtrl, $this);
    if (empty($this->page) or !$this->page['active']) return new Ctrl404($this, new Exception('Page does not exists'));
    elseif (!empty($this->page['link'])) {
      redirect($this->page['link']);
      die();
    }
    elseif (!empty($this->page['controller'])) $controller = PageControllersCore::getController($this, $this->page);
    if (!isset($controller)) $controller = (new CtrlPageVDefault($this))->setPage($this->page);
    R::set('currentPageId', $this->page['id']);
    R::set('breadcrumbsPageIds', empty($this->page['pathData']) ? [] : Arr::get($this->page['pathData'], 'id'));
    return $controller;
  }

  function getOutput() {
    if (!$this->cacheEnable) {
      $html = parent::getOutput();
    }
    elseif (!($html = $this->cache->load($this->cacheKey))) {
      $html = parent::getOutput();
      $this->cache->save($html, $this->cacheKey);
    }
    //$html = Html::baseDomainLinks($html);
    /*
    if ($this->oController->userGroup) {
      $html = preg_replace_callback(
        '/<\!-- Page Layout Begin -->(.*)<\!-- Page Layout End -->/sm',
        function($m) {
          return preg_replace(
            '/a href="\/*([^"])/',
            'a href="'.O::get('SiteRequest')->getAbsBase().'/$1',
            $m[1]
          );
        },
        $html
      );
    }
    */
    //$this->afterRender($html);
    return $html;
  }

  protected function afterRender(&$html) {
  }

  protected $virtualCtrl = false;

  protected function initPage() {
    if (isset($this->req->params[1]) and $this->req->params[0] == 'sbc') return;

    if (isset($this->req->params[0]) and is_numeric($this->req->params[0]) and ($this->page = DbModelCore::get('pages', $this->req->params[0])) !== false) {
      $this->pathRedirect();
    }
    else {
      if (!isset($this->req->params[0]) or $this->req->params[0] == 'index.php') {
        $this->page = new DbModelVirtual([
          'title'    => 'home',
          'id'       => 1,
          'active'   => 1,
          'pathData' => [
            [
              'title' => 'home',
              'id' => 1,
              'link'  => '/'
            ]
          ],
          'settings' => []
        ]);
        //if (($this->page = DbModelPages::getHomepage()) === false) throw new Exception('Homepage not found');
      }
      else {
        $ctrl = $this->req->params[0];
        $routes = Config::getVar('routes');
        if (isset($routes[$this->req->params[0]])) $ctrl = $routes[$this->req->params[0]];
        if (PageControllersCore::virtualCtrlExists($ctrl)) {
          $this->virtualCtrl = $ctrl;
          $this->page = PageControllersCore::getVirtualCtrlPageModel($ctrl);
        }
        elseif (($this->page = DbModelCore::get('pages', $ctrl, 'path')) !== false) {
          // by path
        }
        elseif (($this->page = DbModelCore::get('pages', $ctrl, 'name')) !== false) {
          // by name
        }
      }
    }
  }

  protected function pathRedirect() {
    if (empty($this->page['path'])) return;
    $pathParams2 = (count($this->req->params) > 1) ? '/'.implode('/', array_splice($this->req->params, 1, count($this->req->params))) : '';
    header('Location: /'.$this->page['path'].$pathParams2.($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : ''));
  }

  protected function error404() {
    header('HTTP/1.0 404 Not Found');
  }

  protected function afterOutput() {
    //db()->query(
    //  'INSERT INTO pages_log SET dateCreate=?, pageId=?d, title=?, url=?, processTime=?,memory=?, userId=?d, info=?',
    //  dbCurTime(), $this->page['id'], $this->page['title'], $_SERVER['REQUEST_URI'],
    //  getProcessTime(), memory_get_usage(), Auth::get('id'), serialize(Misc::getHttpClientInfo()));
  }

  protected function auth() {
    parent::auth();
    // Редирект на определенный раздел сразу после авторизации
    /*
    if (
    Auth::$postAuth and
    Config::getVarVar('userReg', 'redirectToFirstPage') and
    ($pageIds = Config::getVarVar('userReg', 'pageIds'))
    ) {
      $path = db()->selectCell('SELECT path FROM pages WHERE id=?d',
        Arr::first(Arr::explodeCommas($pageIds)));
      if (!$path) throw new Exception('Redirecting page does not exists');
       redirect($path);
    }
    */
  }

}