<?php

class CtrlSbPageBlocks extends CtrlDefault {

  protected function getParamActionN() {
    return 3;
  }

  protected $pageId, $page;

  protected function init() {
    $this->pageId = (int)$this->req->param(2);
    $this->page = DbModelCore::get('pages', $this->pageId);
  }

  function action_ajax_default() {}

  protected function jsonCreateBlockTitle() {
    $this->json['title'] = $this->page ? 'Создание блока раздела «'.$this->page['title'].'»' : 'Создание глобального блока';
  }

  protected function newBlockFormAction($formClass) {
    $this->jsonCreateBlockTitle();
    $form = new $formClass(['submitTitle' => 'Продолжить создание блока']);
    if ($form->isSubmittedAndValid()) {
      $this->json['nextFormUrl'] = '/'.$this->tt->getPath(3).'/json_newBlockStep2/'.$this->req->param(4).'/'.$form->elementsData['type'];
      return;
    }
    return $form;
  }

  /**
   * Создание блока. Шаг 1. Выбор типа
   */
  function action_json_newBlock() {
    return $this->newBlockFormAction('PageBlockTypeForm');
  }

  function action_json_newBlockSimple() {
    return $this->newBlo ckFormAction('PageBlockTypeForm');
  }

  /**
   * Шаг 2. Выполняется только при наличии для этого типа pre-полей
   */
  function action_json_newBlockStep2() {
    $this->jsonCreateBlockTitle();
    $oPBS = PageBlockCore::getStructure($this->req->param(5), ['pageId' => $this->pageId]);
    $preFields = $oPBS->getPreFields();
    if ($preFields) {
      $form = new Form(new Fields($preFields), [
        'submitTitle' => 'Продолжить создание блока'
      ]);
      if ($form->isSubmittedAndValid()) {
        $this->json['nextFormUrl'] = $this->tt->getPath(3).'/json_newBlockStep3/'.$this->req->param(4).'/'.$this->req->param(5).'?'.http_build_query($form->getData());
        return;
      }
      return $form;
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
        PageBlockCore::updateColN($v['id'], $v['colN'], $this->pageId);
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
    DbModelCore::get('pageBlocks', $this->req->param(4))->updateGlobal((bool)$this->req->param(5), $this->pageId);
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