<?php

abstract class PageModuleDdMaster extends PageModuleDd {
  
  public $controller = 'ddItemsMaster';
  protected $masterStrName;
  protected $masterTitle;
  protected $masterFields;
  protected $slaveController = 'ddItems';
  protected $slaveTitle;
  protected $slavePageName;
  protected $slaveFields;
  private $slaveModule;
  private $slaveStrName;
  protected $requiredProperties = [
    'masterTitle',
    'masterFields',
    'slavePageName',
    'slaveFields',
  ];
  
  function __construct() {
    parent::__construct();
    $this->slaveModule = $this->module.'Slave';
    if (!isset($this->masterTitle)) $this->masterTitle = $this->title;
    if (!isset($this->masterStrName)) $this->masterStrName = $this->strName;
    $this->slaveStrName = DdCore::getSlaveStrName($this->masterStrName);
  }
  
  protected function getSlaveTitle() {
    return $this->masterTitle.': '.$this->slaveTitle;
  }
  
  protected function afterCreate() {
    $this->createSlavePage($this->page['id']);
  }
  
  protected function createStructure() {
    $this->createMasterStructure();
    $this->createSlaveStructure();
  }
  
  protected function createMasterStructure() {
    if ($this->sm->items->getItemByField('name', $this->masterStrName)) return;
    $this->sm->create([
      'title' => $this->masterTitle,
      'name' => $this->masterStrName
    ]);
    $oDFM = new DdFieldsManager($this->masterStrName);
    foreach ($this->masterFields as $field) {
      $oDFM->create($field);
    }
  }
  
  protected function createSlaveStructure() {
    if ($this->sm->items->getItemByField('name', $this->slaveStrName)) return;
    $this->sm->create([
      'title' => $this->getSlaveTitle(),
      'name' => $this->slaveStrName
    ]);
    $this->createSlaveFields();
  }
  
  protected function createSlaveFields() {
    $oDdFields = new DdFieldsManager($this->slaveStrName);
    $oDdFields->create([
      'name' => DdCore::masterFieldName,
      'title' => $this->masterTitle,
      'type' => 'ddSlaveItemsSelect',
      'required' => true
    ]);
    foreach ($this->slaveFields as $field) {
      $oDdFields->create($field);
    }
  }
  
  protected function createSlavePage($masterPageId) {
    $pageData = [
      'title' => $this->getSlaveTitle(),
      'name' => $this->slavePageName,
      'folder' => 0,
      'active' => 1,
      'onMenu' => 0,
      'onMap' => 0,
      'parentId' => $masterPageId,
      'oid' => 0,
      'controller' => $this->slaveController,
      'module' => $this->slaveModule,
      'slave' => 1,
    ];
    $settings = [
      'strName' => $this->slaveStrName,
      'masterStrName' => $this->masterStrName,
      'masterPageId' => $masterPageId
    ];
    $settings += $this->getSlaveSettings();
    $pageData['settings'] = $settings;
    $slavePageId = DbModelCore::create('pages', $pageData);
    DbModelPages::addSettings($masterPageId, [
      'slavePageId' => $slavePageId,
    ]);
  }
  
  protected function getSettings() {
    return [
      'strName' => $this->masterStrName
    ];
  }

  protected function getSlaveSettings() {
    return [];
  }

}