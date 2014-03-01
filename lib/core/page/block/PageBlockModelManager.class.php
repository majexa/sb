<?php

class PageBlockModelManager extends DbModelManager {

  protected $createParams;

  function __construct(PbsAbstract $oPBS, array $createParams = []) {
    $form = empty($oPBS->preParams['pageId']) ? new Form(new Fields($oPBS->getFields()), ['id' => 'frm'.get_class($oPBS)]) : new DdFormPage(new Fields($oPBS->getFields()), $oPBS->preParams['pageId'], ['id' => 'frm'.get_class($oPBS)]);
    parent::__construct('pageBlocks', $form);
    $this->createParams = $createParams;
    if (($s = $oPBS->getImageSizes()) !== false) $this->imageSizes = array_merge($this->imageSizes, $s);
    $this->smResizeType = 'resample';
  }

  function _updateField($id, $fieldName, $value) {
    $model = DbModelCore::get($this->modelName, $id);
    BracketName::setValue($model->r['settings'], $fieldName, $value);
    $model->save();
  }

  protected function _create() {
    $d = $this->createParams;
    $d['settings'] = $this->data;
    // Костыль для блоков структуры PbsPage
    if (!empty($d['settings']['pageId'])) $d['pageId'] = $d['settings']['pageId'];
    $this->data = $d;
    return parent::_create();
  }

  protected function _update() {
    $this->data = [
      'settings' => array_merge(DbModelCore::get($this->modelName, $this->id)->r['settings'], $this->data)
    ];
    parent::_update();
  }

  function getItem($id) {
    return DbModelCore::get($this->modelName, $id)->r['settings'];
  }

}
