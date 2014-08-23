<?php

/**
 * Особенностью этого типа контроллеров является то, что выполнение dispatch()
 * не возможно пока не быдет успешно выполнен метод setPage()
 */
abstract class CtrlPage extends CtrlCommon /*implements ProcessDynamicPageBlock*/ {

  /**
   * Текущий раздела
   *
   * @var   DbModel
   */
  public $page;

  protected function init() {
    $this->d['name'] = $this->getName();
    parent::init();
  }

  function action_default() {
  }

  protected function getName() {
    return ClassCore::classToName('CtrlPage', get_class($this));
  }

  protected function beforeAction() {
    parent::beforeAction();
    $this->initLayout();
  }

  protected function afterAction() {
    Sflm::frontend('css')->addLib('sb');
    Sflm::frontend('js')->addLib('sb');
    $this->d['body'] = PageLayout::autoHtml($this->d['layoutN'], $this->page['id'], $this);
  }

  protected function initLayout() {
    if (!isset($this->d['layoutN'])) $this->d['layoutN'] = PageLayoutN::get($this->page['id']);
    if ($this->action == 'new' or $this->action == 'edit') $this->d['layoutN'] = 2;
  }

  protected function afterInit() {
    parent::afterInit();
    $this->initHeadTitle();
  }

  function setPageTitle($title, $setEqualPath = false) {
    if (empty($title)) throw new Exception('Title can not be empty', 311);
    $this->setHeadTitle($title);
    parent::setPageTitle($title);
    if ($setEqualPath) {
      $this->d['pathData'][count($this->d['pathData']) - 1] = [
        'title' => $title,
        'path'  => $this->tt->getPath()
      ];
    }
  }

  /**
   * Разрешает отображение неактивных разделов
   *
   * @var bool
   */
  protected $allowNonActivePages = false;

  function setPage(DbModel $page) {
    $this->page = $page;
    if (empty($this->page['active']) and !$this->allowNonActivePages) {
      throw new AccessDenied('Page is not active');
    }
    $this->d['pathData'] = $this->page['pathData'];
    $this->d['page'] = $this->page;
    $title = empty($this->page['fullTitle']) ? $this->page['title'] : $this->page['fullTitle'];
    $this->setHeadTitle($title);
    $this->setPageTitle($title);
    return $this;
  }

  private function initHeadTitle() {
    if (empty($this->d['pageMeta']['title'])) return;
    if ($this->d['pageMeta']['titleType'] == 'add') {
      $this->setHeadTitle($this->d['pageMeta']['title']);
    }
    else {
      $this->d['pageHeadTitle'] = $this->d['pageMeta']['title'];
    }
  }

  protected function setHeadTitle($title) {
    if (Config::getVarVar('layout', 'pageTitleFormat') == 1) {
      $this->d['pageHeadTitle'] = SITE_TITLE.' — '.$title;
    }
    else {
      $this->d['pageHeadTitle'] = $title.' — '.SITE_TITLE;
    }
  }

  protected function denialAuthorize() {
    $this->d['tpl'] = 'common/denialAuthorize';
  }

  protected function extendTplData() {
    if (isset($this->page['settings']['mainTpl'])) $this->d['mainTpl'] = $this->page['settings']['mainTpl'];
    if (($paths = Hook::paths('before', $this->page['module'])) !== false) {
      foreach ($paths as $path) include $path;
    }
    if (($paths = Hook::paths('pageNames/'.$this->page['name'])) !== false) {
      foreach ($paths as $path) include $path;
    }
    if (($paths = Hook::paths('after', $this->page['module'])) !== false) {
      foreach ($paths as $path) include $path;
    }
  }

  protected function prepareTplPath() {
    if (!empty($this->page['module']) and $this->tt->exists('pageModules/'.$this->page['module'].'/'.$this->d['tpl'])) {
      $this->d['tpl'] = 'pageModules/'.$this->page['module'].'/'.$this->d['tpl'];
    }
  }

  /**
   * Добавляет в ссылки пути ссылку на текущую страницу
   *
   * @param string $title Заголовок ссылки
   */
  function setCurrentPathData($title) {
    $this->setPathData($this->tt->getPath(), $title);
  }

  function setPathData($path, $title) {
    if (!$title) return;
    $this->d['pathData'][] = [
      'title' => $title,
      'link'  => $path
    ];
  }

  protected function setPathDataBeforeLast($path, $title) {
    $n = count($this->d['pathData']);
    $newPathData = [];
    for ($i = 0; $i < $n; $i++) {
      if ($i == $n - 1) {
        $newPathData[] = [
          'title' => $title,
          'link'  => $path
        ];
      }
      $newPathData[] = $this->d['pathData'][$i];
    }
    $this->d['pathData'] = $newPathData;
  }

  function setPathDataLast($title, $path = null) {
    $n = count($this->d['pathData']) - 1;
    $this->d['pathData'][$n] = [
      'title' => $title,
      'link'  => $path ? $path : $this->d['pathData'][$n]['link']
    ];
  }

  protected function resetPathData($path, $title) {
    $this->d['pathData'] = [
      [
        'title' => $title,
        'link'  => $path
      ]
    ];
  }

  function action_editPage() {
    $this->d['id'] = $this->page['id'];
    $this->d['title'] = $this->page['title'];
    $this->d['tpl'] = 'common/editPage';
  }

  function action_updateTitle() {
    if (!isset($this->req->r['title'])) throw new Exception("\$this->req->r['title'] not defined");
    $oPages = new PagesAdmin();
    $oPages->updateTitle($this->page['id'], $this->req->r['title']);
    $this->redirect();
  }

  function error404($title = 'Page not found', $text = '') {
    parent::error404($title, $text);
    if ($this->hasOutput) {
      $this->setPathData($this->tt->getPath(), $title);
      $this->setPageTitle($title);
    }
  }

  /**
   * Использовать каталог с шаблонами по-умолчанию, не учитывая шаблон
   * указанный в настройках раздела
   *
   * @var bool
   */
  protected $useDefaultTplFolder = false;

  protected function initAction() {
    // Если в настройках раздела определен экшн по умолчанию
    if (!empty($this->page['settings']['defaultAction'])) {
      $this->defaultAction = $this->page['settings']['defaultAction'];
    }
    parent::initAction();
  }

}
