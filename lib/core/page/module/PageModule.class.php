<?php

// это не PageModuleInstaller, это PageInstaller
// каждая страница имеет свою уникальную структуру
// не имеет смысла создавать структуру для каких-то ещё разделов
// следовательно инсталлировать ничего не нужно
// в рамках SB структура удяляется вместе с разделом
// значит
abstract class PageModule {
  use Options;

  protected $module;

  /**
   * @var bool Допускает только один раздел модуля на проект
   */
  protected $singleModule = false;

  public $title;
  public $description;
  public $controller = '';
  public $onMenu = true;
  public $updateControllerAfterNodeCreate = false;
  public $oid = 0;
  protected $requiredProperties = [];
  protected $behaviorNames = [];
  protected $pageBlocks = [];

  function __construct() {
    $this->module = ClassCore::classToName('PageModule', $this);
    if ($this->controller == 'module') $this->controller = $this->module;
    Misc::checkEmpty($this->title);
    foreach ($this->requiredProperties as $name) if (!isset($this->$name)) throw new Exception("\$this->$name not defined. Class: ".get_class($this));
  }

  /**
   * Инсталлирует раздел
   * Пример массива $node:
   * [
   *   [oid] => 11
   *   [title] => Оригинальные картриджи
   *   [parentId] => 10
   *   [onMenu] => true
   * ]
   *
   * @param   array
   * @return  DbModelPages Созданный раздел
   */
  function create(array $node = []) {
    $node = $this->prepareNode($node);
    $this->createNode($node);
    $this->afterCreate();
    $this->createPageBlocks();
    return $this->page;
  }


  protected function prepareNode(array $node) {
    if (empty($node['title'])) $node['title'] = $this->title;
    if (empty($node['name'])) $node['name'] = Misc::transit($node['title'], true);
    if (empty($node['parentId'])) $node['parentId'] = -1;
    $node['onMap'] = 1;
    $node['onMenu'] = !empty($node['onMenu']) ? $node['onMenu'] : $this->onMenu;
    $node['active'] = 1;
    $node['folder'] = !empty($node['folder']) ? 1 : (empty($node['children']) ? 0 : 1);
    $node['module'] = $this->module;
    if (!$this->updateControllerAfterNodeCreate) $node['controller'] = $this->controller;
    //unset($node['n']);
    return $node;
  }

  protected function createNode(array $node) {
    if (($this->page = DbModelCore::get('pages', $node['name'], 'name'))) {
      throw new Exception("Page with name '{$node['name']}' already exists");
    }
    $pageId = DbModelCore::create('pages', $node, true);
    if ($this->updateControllerAfterNodeCreate) {
      db()->query('UPDATE pages SET controller=? WHERE id=?d', $this->controller, $pageId);
    }
    $this->page = DbModelCore::get('pages', $pageId);
    return $this->page['id'];
  }

  protected $page;

  protected function getSettings() {
    return [];
  }

  protected function afterCreate() {
  }

  protected function createPageBlocks() {
    foreach ($this->pageBlocks as $v) {
      $params = [
        'type'      => $v['type'],
        'ownPageId' => $this->page['id'],
        'colN'      => 1,
        'global'    => false
      ];
      if (!empty($v['params'])) $params = array_merge($params, $v['params']);
      $manager = new PageBlockModelManager(PageBlockCore::getStructure($v['type'])->setPreParams([
        'pageId' => $this->page['id']
      ]), $params);
      $manager->create($v['settings']);
    }
  }

  /**
   * @param  string $module
   * @return PageModule
   */
  static function get($module) {
    return O::get('PageModule'.ucfirst($module));
  }

  /**
   * @param  string $module
   * @return PageModule
   */
  static function take($module) {
    return O::take('PageModule'.ucfirst($module));
  }

}
