<?php

class CtrlSbPageBlocks extends CtrlDefault {

  protected function getParamActionN() {
    return 3;
  }

  protected $page;

  protected function init() {
    $this->hasOutput = false;
    $this->page = PageControllersCore::getPageModel($this->req->param(2));
  }

  protected function jsonCreateBlockTitle() {
    $this->json['title'] = $this->page ? 'Создание блока раздела «'.$this->page['title'].'»' : 'Создание глобального блока';
  }

  protected function newBlockFormAction($formClass) {
    $this->jsonCreateBlockTitle();
    $form = new $formClass(['submitTitle' => 'Продолжить создание блока']);
    if ($form->isSubmittedAndValid()) {
      $this->json['nextFormUrl'] = $this->tt->getPath(3).'/json_newBlockStep2/'.$this->req->param(4).'/'.$form->elementsData['type'];
      $this->json['nextFormOptions'] = [
        'width' => 350
      ];
      return null;
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
    return $this->newBlockFormAction('PageBlockTypeForm');
  }

  /**
   * Шаг 2. Выполняется только при наличии для этого типа pre-полей
   */
  function action_json_newBlockStep2() {
    $this->jsonCreateBlockTitle();
    $pbs = PageBlockCore::getStructure($this->req->param(5), ['pageId' => $this->page['id']]);
    $preFields = $pbs->getPreFields();
    if ($preFields) {
      $form = new Form(new Fields($preFields), [
        'submitTitle' => 'Продолжить создание блока'
      ]);
      if ($form->isSubmittedAndValid()) {
        $this->json['nextFormUrl'] = $this->tt->getPath(3).'/json_newBlockStep3/'.$this->req->param(4).'/'.$this->req->param(5).'?'.http_build_query($form->getData());
        $this->json['nextFormOptions'] = [
          'width' => 350
        ];
        return null;
      }
      return $form;
    }
    else {
      return $this->action_json_newBlockStep3();
    }
  }

  /**
   * Финальный шаг. Создание блока
   */
  function action_json_newBlockStep3() {
    $this->jsonCreateBlockTitle();
    $structure = PageBlockCore::getStructure($this->req->param(5));
    if (!empty($this->req->g)) $structure->setPreParams($this->req->g);
    $createData = [
      'ownPageId' => $this->page['id'],
      'type'      => $this->req->param(5),
      'colN'      => $this->req->param(4),
      'global'    => empty($this->page['id'])
    ];
    $manager = new PageBlockModelManager($structure, $createData);

    if ($manager->requestCreate()) return null;
    $this->json['jsOptions'] = ['onOkClose' => 'func: window.location.reload(true)'];
    return $manager->form;
  }

  function action_ajax_deleteBlock() {
    PageBlockCore::delete($this->req->rq('blockId'));
  }

  function action_ajax_updateBlocks() {
    $ids = [];
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
    $pbs = PageBlockCore::getStructure($model['type']);
    $pbs->setPreParamsBySettings($model['settings']);
    $oMM = new PageBlockModelManager($pbs);
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
    PageBlockCore::createGlobalBlocksDuplicates($this->page['id']);
    $this->redirect($this->tt->getPath(3));
  }

  function action_deleteGlobalBlocksDuplicates() {
    PageBlockCore::deleteDuplicateBlocks($this->page['id']);
    $this->redirect($this->tt->getPath(3));
  }

}