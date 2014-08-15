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

  /**
   * Массив с настройками текущего раздела
   *
   * @var   array
   */
  public $settings;

  /**
   * Поля настроект текущего раздела, которые обязательно должны быть определены
   *
   * @var   array
   */
  public $requiredSettings = [];

  protected $defaultSettings = [];

  /**
   * Пака с шаблонами. По умолчанию - 'dd'
   *
   * @var string
   */
  public $tplFolder = 'dd';

  /**
   * Идентификатор необходим для инициализации объекта комментов
   *
   * @var integer
   */
  protected $id2;

  /**
   * ID текущего авторизованого пользователя
   *
   * @var integer
   */
  public $userId;

  /**
   * Свойства клавного шаблона
   *
   * @var array
   */
  protected $mainTplProperties;

  function dispatch() {
    if (!isset($this->page)) throw new Exception('page model not defined');
    if (isset($this->page['settings']['mainTpl'])) $this->d['mainTpl'] = $this->page['settings']['mainTpl'];
    parent::dispatch();
    if ($this->hasOutput) {
      Sflm::frontend('css')->addLib('sb');
      Sflm::frontend('js')->addLib('sb');
    }
  }

  protected function getParamActionN() {
    return 1;
  }

  protected function init() {
    $this->d['name'] = $this->getName();
    $this->setActionParams();
    $this->initPriv();
    parent::init();
  }

  function action_default() {
    $this->d['body'] = PageLayout::autoHtml($this->d['layoutN'], $this->page['id'], $this);
  }

  protected function getName() {
    return ClassCore::classToName('CtrlPage', get_class($this));
  }

  function beforeAction() {
    parent::beforeAction();
    $this->initLayout();
  }

  protected function initLayout() {
    if (!isset($this->d['layoutN'])) $this->d['layoutN'] = PageLayoutN::get($this->page['id']);
    if ($this->action == 'new' or $this->action == 'edit') $this->d['layoutN'] = 2;
  }

  protected function afterInit() {
    parent::afterInit();
    $this->initHeadTitle();
  }

  function setActionParams() {
    $this->actionParams['json_citySearch'] = [
      [
        'method' => 'request',
        'name'   => 'mask'
      ]
    ];
    $this->actionParams['json_citySearch'] = [
      [
        'method' => 'request',
        'name'   => 'mask'
      ]
    ];
    $this->actionParams['json_userSearch'] = $this->actionParams['json_citySearch'];
    $this->actionParams['updatePage'] = ['name' => 'title'];
  }

  /**
   * Флаг раздрешает отображение неактивных разделов
   *
   * @var bool
   */
  public $allowNonActivePages = false;

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

  function setPage(DbModel $page) {
    $this->page = $page;
    if (!$this->page) {
      $this->error404();
      return;
    }

    if (empty($this->page['active']) and !$this->allowNonActivePages) {
      $this->error404('Page is not active');
      return;
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
    if (empty($this->page)) return;
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
   * Каталог с шаблонами для админки
   *
   * @var string
   */
  public $adminTplFolder;

  /**
   * Флаг определяет, что контроллер был вызван из админки
   *
   * @var bool
   */
  public $adminMode = false;

  /**
   * Использовать каталог с шаблонами по-умолчанию, не учитывая шаблон
   * указанный в настройках раздела
   *
   * @var bool
   */
  protected $useDefaultTplFolder = false;

  /**
   * Массив, в котором каждая привилегия определяет те экшены (без layout-префиксов),
   * которые она разрешает
   *
   * @var   array
   */
  public $actionByPriv = [
    'view'       => ['default'],
    'moder'      => ['edit', 'new', 'moveForm', 'move', 'delete', 'activate', 'deactivate', 'publish'],
    'edit'       => ['edit', 'update', 'delete', 'move', 'activate', 'deactivate', 'deleteFile'],
    'editOnly'   => ['edit', 'delete'],
    'admin'      => ['updateDirect', 'deleteGroup', 'deleteFile'],
    'create'     => ['new'],
    'sub_edit'   => ['sub_edit', 'sub_update', 'sub_delete', 'sub_activate', 'sub_deactivate'],
    'sub_create' => ['sub_create']
  ];

  public $privByAction;

  public $allowedActions;

  /**
   * Определяет возможность редактирования текущим пользователем данного раздела/записи
   * см. дальнейшую реализацию метода в наследуемых классах
   */
  protected function initPriv() {
    $this->initActionsByPriv();
    $this->_initPriv();
    $this->d['privAuth'] = $this->priv->getAuthPriv();
    $this->d['priv'] = $this->priv;
    $this->initAllowedActions();
  }

  /**
   * @var PagePriv
   */
  public $priv;

  protected function _initPriv() {
    $this->priv = new PagePriv($this->page, $this->userId);
  }

  // Дозволеные экшены
  protected function _initAllowedActions() {
    $this->allowedActions = [];
    foreach (array_keys($this->priv->r) as $priv) {
      if (isset($this->actionByPriv[$priv])) {
        foreach ($this->actionByPriv[$priv] as $action) $this->allowedActions[] = $action;
      }
    }
  }

  protected function initAllowedActions() {
    $this->_initAllowedActions();
    $this->d['allowedActions'] = $this->allowedActions;
  }

  protected function setModers() {
    $this->d['moders'] = $this->oPriv->getUsers($this->page['id'], 'edit');
  }

  /**
   * Определяет дополнительные привилегии после инициализации
   *
   * @param  string  Имя привилегии
   * @param  bool    Флаг "разрешено/запрещено"
   */
  protected function setPriv($name, $flag) {
    $this->priv[$name] = $flag;
    $this->d['priv'][$name] = $flag;
    $this->initAllowedActions();
  }

  protected function setPrivs(array $names, $flag) {
    foreach ($names as $name) {
      $this->priv[$name] = $flag;
      $this->d['priv'][$name] = $flag;
    }
    $this->initAllowedActions();
  }

  /**
   * Разрешить ли данный экшен
   *
   * @param   string    Имя экшена
   * @return  bool
   */
  protected function allowAction($action) {
    $action = $this->clearActionPrefixes($action);
    if (!isset($this->priv)) return true;
    // Если для экшена нет привилегий, значит по умолчанию он разрешен
    if (!isset($this->privByAction[$action])) return true;
    return in_array($action, $this->allowedActions);
  }

  protected function initActionsByPriv() {
    foreach ($this->actionByPriv as $priv => $actions) {
      foreach ($actions as $action) {
        $this->privByAction[$action] = $priv;
      }
    }
  }

  protected $allowAuthorized = false;
  protected $allowAuthorizedActions = [];

  protected function action() {
    if (!isset($this->priv)) {
      parent::action();
      return;
    }
    if (empty($this->priv['view'])) {
      $this->error404();
      return;
    }
    if ($this->allowAuthorized and !Auth::get('id')) {
      $this->d['tpl'] = 'denialAuthorize';
      return;
    }
    elseif ($this->allowAuthorizedActions and in_array($this->action, $this->allowAuthorizedActions)) {
      $this->d['tpl'] = 'denialAuthorize';
      return;
    }
    elseif (!$this->allowAction($this->action) or $this->req['editNotAllowed']) {
      //$this->error404('Действие запрещено');
      throw new AccessDenied;
      return;
    }
    parent::action();
  }

  protected function initAction() {
    // Если в настройках раздела определен экшн по умолчанию
    if (!empty($this->page['settings']['defaultAction'])) {
      $this->defaultAction = $this->page['settings']['defaultAction'];
    }
    parent::initAction();
  }

}
