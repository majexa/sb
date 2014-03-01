<?php

class CtrlAdminSlices extends CtrlAdminPagesBase {

  static $properties = [
    'title'  => 'Слайсы',
    'onMenu' => true,
    'order'  => 5
  ];

  protected function initAction() {
    if (isset($this->req->params[2])) $this->defaultAction = 'edit';
    parent::initAction();
  }

  function action_default() {
    $this->setPageTitle('Все слайсы');
    $this->d['items'] = db()->query('
    SELECT
      slices.*,
      p.title AS pageTitle,
      p.path AS pagePath,
      p2.title AS pageTitle2,
      p2.path AS pagePath2
    FROM slices
    LEFT JOIN pages AS p ON slices.pageId=p.id
    LEFT JOIN pages AS p2 ON p.parentId=p2.id
    ');
  }

  function action_new() {
    $form = new Form(new Fields([
      [
        'title'    => 'Название',
        'name'     => 'title',
        'required' => true
      ], [
        'title'    => 'ID',
        'name'     => 'id',
        'type'     => 'name',
        'required' => true
      ], [
        'title' => 'Текст',
        'name'  => 'text',
        'type'  => 'wisiwig'
      ]
    ]));
    if ($this->pageId) {
      $this->setPageTitle('Создание слайса для раздела «'.$this->d['page']['title'].'»');
    }
    else {
      $this->setPageTitle('Создание глобального слайса');
    }
    if ($form->isSubmittedAndValid()) {
      $data['pageId'] = $this->pageId;
      Slice::replace($data);
      $this->redirect($this->tt->getPath(2).'/'.$data['id']);
    }
    $this->d['form'] = $form->html();
    $this->d['tpl'] = 'slices/new';
  }

  function action_edit() {
    if (!($slice = DbModelCore::get('slices', $this->req->params[3]))) throw new Exception("Slice id={$this->req->params[3]} does not exists");
    $this->d['slice'] = $slice;
    $this->setPagesPath($slice['pageId']);
    $this->setPageTitle('Редактирование: <b>'.$slice['title'].'</b>'.' раздела «'.$this->d['page']['title'].'»');
    $this->d['attachId'] = $this->req->params[3];
    $this->d['tpl'] = 'slices/edit';
  }

  function action_update() {
    Slice::replace(array_merge(['id' => $this->req->param(3)], $_POST));
    $this->redirect();
  }

  function action_delete() {
    DbModelCore::delete('slices', $this->req->param(3));
    $this->redirect($this->tt->getPath(2));
  }

}
