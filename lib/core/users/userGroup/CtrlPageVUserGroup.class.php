<?php

class CtrlPageVUserGroup extends CtrlPageUserGroupBase {

  protected function init() {
    parent::init();
    $this->addSubController(new SubPaTagsTreeUserGroup($this));
  }

  protected function getManager() {
    $oF = new Form(new Fields([
      [
        'title' => 'Название',
        'name' => 'title',
        'required' => true
      ],
      [
        'title' => 'Домен',
        'name' => 'name',
        'type' => 'domain',
        'required' => true
      ],
      [
        'title' => 'Картинка',
        'name' => 'image',
        'type' => 'imagePreview',
        'required' => true
      ],
      [
        'title' => 'Описание',
        'name' => 'text',
        'type' => 'wisiwigSimple'
      ],
    ]));
    $m = new DbModelManager('userGroup', $oF);
    $m->imageSizes['mdW'] = 180;
    $m->imageSizes['mdH'] = 800;
    return $m;
  }

  function action_json_new() {
    $this->json['title'] = 'Создание сообщества';
    $m = $this->getManager();
    if ($m->requestCreate()) return;
    return $this->jsonFormAction($m->form);
  }
  
  function action_json_edit() {
    $this->json['title'] = 'Редактирование сообщества';
    $m = $this->getManager();
    if ($m->requestUpdate($this->req->rq('id'))) return;
    return $this->jsonFormAction($m->form);
  }
  
}
