<?php

class CtrlAdminPageBlocks extends CtrlAdminPagesBase {

  static $properties = [
    'title'  => 'Блоки',
    //'onMenu' => true,
    'order'  => 320
  ];

  protected $blockColN;
  protected $blockType;
  protected $globalBlocksAdded;

  protected function init() {
    parent::init();
    $this->d['globalBlocksAdded'] = PageBlockCore::globalBlocksDuplicatesExists($this->pageId);
  }

  function action_default() {
    if ($this->pageId) {
      $this->setPageTitle('Блоки раздела «<b>'.$this->page['title'].'</b>»');
    }
    else {
      $this->setPageTitle('Блоки по умолчанию');
    }
    $cols = PageLayout::getColsByLayout($this->pageId);
    $this->d['colsNumber'] = count($cols);
    $this->d['blocks'] = PageBlockCore::sortBlocks(PageBlockCore::getDynamicBlocks($this->pageId), $this->d['colsNumber']);
    $this->d['cols'] = PageLayout::getColsByLayout($this->pageId);
  }

  protected function jsonCreateBlockTitle() {
    $this->json['title'] = (isset($this->page) ? 'Создание блока раздела «'.$this->page['title'].'»' : 'Создание глобального блока');
  }

  protected function newBlockFormAction($formClass) {
    $this->jsonCreateBlockTitle();
    $oF = new $formClass(['submitTitle' => 'Продолжить создание блока']);
    if ($oF->isSubmittedAndValid()) {
      $this->json['nextFormUrl'] = '/'.$this->tt->getPath(3).'/json_newBlockStep2/'.$this->req->param(4).'/'.$oF->elementsData['type'];
      return;
    }
    return $oF;
  }

  /**
   * Создание блока. Шаг 1. Выбор типа
   */
  function action_json_newBlock() {
    return $this->newBlockFormAction('PageBlockTypeForm');
  }

  function action_json_newBlockSimple() {
    return $this->newBlockFormAction('PageBlockTypeSimpleForm');
  }

  /**
   * Шаг 2. Выполняется только при наличии для этого типа pre-полей
   */
  function action_json_newBlockStep2() {
    $this->jsonCreateBlockTitle();
    $oPBS = PageBlockCore::getStructure($this->req->param(5), ['pageId' => $this->pageId]);
    $preFields = $oPBS->getPreFields();
    if ($preFields) {
      $oF = new Form(new Fields($preFields), [
        'submitTitle' => 'Продолжить создание блока'
      ]);
      if ($oF->isSubmittedAndValid()) {
        $this->json['nextFormUrl'] = $this->tt->getPath(3).'/json_newBlockStep3/'.$this->req->param(4).'/'.$this->req->param(5).'?'.http_build_query($oF->getData());
        return;
      }
      return $oF;
    }
    else {
      //$query = (($hiddenParams = $oPBS->getHiddenParams()) != null) ? '?'.http_build_query($hiddenParams) : '';
      return $this->action_json_newBlockStep3();
    }
  }

  /**
   * Финальный шаг. Создание блока
   */
  function action_json_newBlockStep3() {
    $this->jsonCreateBlockTitle();
    $structure = PageBlockCore::getStructure($this->req->param(5));
    if (!empty($_GET)) $structure->setPreParams($_GET);
    $createData = [
      'ownPageId' => $this->pageId,
      'type'      => $this->req->param(5),
      'colN'      => $this->req->param(4),
      'global'    => empty($this->pageId)
    ];
    $mm = new PageBlockModelManager($structure, $createData);
    if ($mm->requestCreate()) return;
    $this->json['jsOptions'] = ['onOkClose' => 'func: window.location.reload(true)'];
    return $mm->form;
  }

  function action_ajax_deleteBlock() {
    PageBlockCore::delete($this->req->rq('blockId'));
  }

  function action_ajax_updateBlocks() {
    foreach ($this->req->rq('cols') as $cols) {
      foreach ($cols as $v) {
        $r[$v['colN']][] = $v['id'];
        PageBlockCore::updateColN($v['id'], $v['colN'], $this->page['id']);
        $ids[] = $v['id'];
      }
    }
    DbShift::items($ids, 'pageBlocks');
  }

  function action_json_editBlock() {
    $id = $this->req->param(4);
    $model = DbModelCore::get('pageBlocks', $id);
    $oPBS = PageBlockCore::getStructure($model['type']);
    $oPBS->setPreParamsBySettings($model['settings']);
    $oMM = new PageBlockModelManager($oPBS);
    if ($oMM->requestUpdate($id)) return;
    $this->json['title'] = 'Редактирование блока «'.PageBlockCore::getTitle($model['type']).'»';
    return $oMM->form;
  }

  function action_ajax_setGlobal() {
    DbModelCore::get('pageBlocks', $this->req->param(4))->updateGlobal((bool)$this->req->param(5), $this->page['id']);
  }

  function action_ajax_getBlock() {
    $r = PageBlockCore::getBlockHtmlData(DbModelCore::get('pageBlocks', $this->req->param(4)));
    $this->ajaxOutput = $r['html'];
  }

  function action_createGlobalBlocksDuplicates() {
    PageBlockCore::createGlobalBlocksDuplicates($this->pageId);
    $this->redirect($this->tt->getPath(3));
  }

  function action_deleteGlobalBlocksDuplicates() {
    PageBlockCore::deleteDuplicateBlocks($this->pageId);
    $this->redirect($this->tt->getPath(3));
  }

}