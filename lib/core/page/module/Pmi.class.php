<?php

abstract class Pmi {
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
    $this->module = ClassCore::classToName('Pmi', $this);
    if ($this->controller == 'module') $this->controller = $this->module;
    Misc::checkEmpty($this->title);
    foreach ($this->requiredProperties as $name) if (!isset($this->$name)) throw new Exception("\$this->$name not defined. Class: ".get_class($this));
  }

  protected function createNode($v) {
    $v['module'] = $this->module;
    if (!$this->updateControllerAfterNodeCreate) $v['controller'] = $this->controller;
    $v['settings'] = $this->getSettings();
    $v['onMap'] = 1;
    $v['onMenu'] = !empty($v['onMenu']) ? $v['onMenu'] : $this->onMenu;
    $v['active'] = 1;
    $v['folder'] = !empty($v['folder']) ? 1 : (empty($v['children']) ? 0 : 1);
    $v['name'] = !empty($v['name']) ? $v['name'] : $this->module;
    if (empty($v['title'])) $v['title'] = $this->title;
    unset($v['n']);
    if (($this->page = DbModelCore::get('pages', $v['name'], 'name'))) {
      throw new Exception("Page with name '{$v['name']}' already exists");
    }
    $pageId = DbModelCore::create('pages', $v, true);
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
   * @return  integer   ID раздела
   */
  function install(array $node = null) {
    $this->createNode($node);
    $this->afterCreate($node);
    $this->createPageBlocks();
    return $this->page['id'];
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
      $oMM = new PageBlockModelManager(PageBlockCore::getStructure($v['type'])->setPreParams([
          'pageId' => $this->page['id']
        ]), $params);
      $oMM->create($v['settings']);
    }
  }

  /**
   * @param  string $module
   * @return Pmi
   */
  static function get($module) {
    return O::get('Pmi'.ucfirst($module));
  }

  /**
   * @param  string $module
   * @return Pmi
   */
  static function take($module) {
    return O::take('Pmi'.ucfirst($module));
  }

}
