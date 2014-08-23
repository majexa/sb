<?php

abstract class PageModuleDd extends PageModule {

  /**
   * @var DdStructuresManager
   */
  protected $sm;

  public $controller = 'ddItems';
  public $hasTopSlice = true;
  public $hasBottomSlice = false;
  protected $strName;
  protected $ddFields;
  protected $requiredProperties = [
    'ddFields',
  ];

  function __construct(array $options = []) {
    parent::__construct($options);
    $this->strName = $this->module;
    $this->sm = new DdStructuresManager();
    $this->init();
  }

  protected function init() {
  }

  protected $strType = 'dynamic';

  protected function createStructure() {
    if ($this->sm->items->getItemByField('name', $this->strName)) return;
    if (!$this->sm->create([
      'title' => $this->title,
      'name'  => $this->strName,
      'type'  => $this->strType
    ])
    ) {
      throw new Exception($this->sm->form->lastError);
    }
    $fm = new DdFieldsManager($this->strName);
    foreach ($this->ddFields as $field) {
      if (!$fm->create($field)) throw new Exception($fm->form->lastError);
    }
  }

  function create(array $node = null) {
    $this->createStructure();
    $node['strName'] = $this->strName;
    parent::create($node);
    $this->createSampleOnRequiredTags();
    //$this->createSlices($this->page['id'], $this->page['title']);
    //$this->updatePageLayout();
    return $this->page['id'];
  }

  function delete($pageId) {
    (new DdStructuresManager)->deleteByName(DbModelCore::get('pages', $pageId)['strName']);
    parent::delete($pageId);
  }

  protected function getSettings() {
    return [
      'strName' => $this->strName
    ];
  }

  protected function createSlices($pageId, $pageTitle) {
    throw new Exception('slices is temporary deprecated');
    if (!ClassCore::hasAncestor('Ctrl'.$this->controller, 'CtrlDdItems')) return;
    if ($this->hasTopSlice) {
      Slice::replace([
        'id'     => 'beforeDdItems_'.$pageId,
        'pageId' => $pageId,
        'title'  => 'Блок над записями: '.$pageTitle,
        'text'   => ''
      ]);
    }
    if ($this->hasBottomSlice) {
      Slice::replace([
        'id'     => 'afterDdItems_'.$pageId,
        'pageId' => $pageId,
        'title'  => 'Блок под записями: '.$pageTitle,
        'text'   => ''
      ]);
    }
  }

  protected $pageLayout = false;

  protected function updatePageLayout() {
    if ($this->pageLayout === false) return;
    PageLayoutN::save($this->page['id'], $this->pageLayout);
  }

  protected function createSampleOnRequiredTags() {
    foreach ((new DdFields($this->strName))->getTagFields() as $name => $field) {
      if (!$field['required']) continue;
      $tags = DdTags::get($this->strName, $name);
      for ($i=1; $i<=3; $i++) $tags->create(['title' => $field['title'].' '.$i]);
    }
  }

} 
