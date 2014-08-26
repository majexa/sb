<?php

abstract class PageModuleDd extends PageModule {

  /**
   * @var DdStructuresManager
   */
  protected $sm;

  public $controller = 'ddItems';
  public $hasTopSlice = true;
  public $hasBottomSlice = false;
  protected $ddFields;
  protected $requiredProperties = [
    'ddFields',
  ];

  function __construct(array $options = []) {
    parent::__construct($options);
    $this->sm = new DdStructuresManager();
    $this->init();
  }

  protected function init() {
  }

  protected function prepareNode(array $node) {
    $node = parent::prepareNode($node);
    $node['settings'] = [
      'strName' => $node['name']
    ];
    return $node;
  }

  protected $strType = 'dynamic';

  protected function createStructure() {
    if ($this->sm->items->getItemByField('name', $this->page['strName'])) return;
    if (!$this->sm->create([
      'title' => $this->title,
      'name'  => $this->page['strName'],
      'type'  => $this->strType
    ])
    ) {
      throw new Exception($this->sm->form->lastError);
    }
    $fm = new DdFieldsManager($this->page['strName']);
    foreach ($this->ddFields as $field) {
      if (!$fm->create($field)) throw new Exception($fm->form->lastError);
    }
  }

  function afterCreate() {
    $this->createStructure();
    $this->createSampleOnRequiredTags();
    //$this->createSlices($this->page['id'], $this->page['title']);
    //$this->updatePageLayout();
    return $this->page['id'];
  }

  /*
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
  */

  protected $pageLayout = false;

  protected function updatePageLayout() {
    if ($this->pageLayout === false) return;
    PageLayoutN::save($this->page['id'], $this->pageLayout);
  }

  protected function createSampleOnRequiredTags() {
    foreach ((new DdFields($this->page['strName']))->getTagFields() as $name => $field) {
      if (!$field['required']) continue;
      $tags = DdTags::get($this->page['strName'], $name);
      for ($i = 1; $i <= 3; $i++) $tags->create(['title' => $field['title'].' '.$i]);
    }
  }

} 
