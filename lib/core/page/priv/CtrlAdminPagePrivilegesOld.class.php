<?php

class CtrlAdminPagePrivilegesOld extends CtrlAdminPagesBase {

  static $properties = [
    'title' => 'Привилегии',
    'descr' => 'Привилегии пользователей',
    'onMenu' => true,
    'order' => 40
  ];
  
  protected $allowSlavePage = false;
  
  /**
   * @var PrivilegesManager
   */
  public $oPrivileges;

  /**
   * @var DdPrivilegesManager
   */
  public $oDDPrivileges;

  protected function init() {
    parent::init();
    $this->oPrivileges = new PrivilegesManager();
    $this->oDDPrivileges = new DdPrivilegesManager();
  }

  function action_default() {
    $this->d['tpl'] = 'privileges/default';
    $this->d['items'] = $this->oPrivileges->getAll();
  }

  function action_new() {
    $this->isDefaultAction = false;
    $this->d['types'] = $this->oPrivileges->getTypes();
    $this->d['tpl'] = 'privileges/add';
  }

  function action_create() {
    $this->oPrivileges->addPrivs($this->req->r['userId'], $this->req->r['pageId'], $this->req->r['types']);
    $this->redirect($this->tt->getPath(2));
  }

  function action_delete() {
    $this->oPrivileges->deleteByPage($this->pageId);
    $this->redirect($this->tt->getPath(2));
  }

  function action_updateByUser() {
    $this->oPrivileges->addPrivsByUser($this->req->r['userId'],
      isset($this->req->r['priv']) ? $this->req->r['priv'] : null);
    $this->redirect('referer');
  }

  function action_updateByPage() {
    $this->oPrivileges->addPrivsByPage($this->pageId, $this->req->r['priv']);
    $this->redirect('referer');
  }

  function action_updateDD() {
    if (! $this->req->r['userId'])
      throw new Exception("\$this->req->r['userId'] not defined");
    $this->oDDPrivileges->addPrivs_array($this->req->r['userId'], $_POST['privs']);
    $this->redirect('referer');
  }

  function action_userPrivileges() {
    if (! $this->req->r['userId'])
      throw new Exception('$this->req->r[\'userId\'] not defined');
    $this->d['tpl'] = 'privileges/userPrivileges';
    $this->d['user'] = DbModelCore::get('users', $this->req->r['userId']);
    $this->d['ddPrivs'] = $this->oDDPrivileges->getByUser($this->req->r['userId']);
    $this->d['ddFields'] = $this->oDDPrivileges->getFields();
    $this->d['ddActions'] = $this->oDDPrivileges->getActions();
    $this->d['types'] = $this->oPrivileges->getTypes();
    $this->d['privs'] = $this->oPrivileges->getByUser($this->req->r['userId']);
    $this->setPageTitle(
      'Привилегии для пользователя <a href="' . TplAdmin::getUserPath(
        $this->req->r['userId']) . '">' . $this->d['user']['login'] . '</a>');
  }

  function action_pagePrivileges() {
    $this->d['tpl'] = 'privileges/pagePrivileges';
    $this->d['types'] = $this->oPrivileges->getTypes();
    $this->d['privs'] = $this->oPrivileges->getByPage($this->pageId);
    $this->setPageTitle('Привилегии для раздела «' . $this->d['page']['title'] . '»');
  }

  function action_cleanup() {
    $this->oPrivileges->cleanup();
    $this->redirect();
  }

  function action_lockPage() {
    if (!$this->pageId)
      throw new Exception('$this->pageId not defined');
    Pages::updateS($this->pageId, [
      'isLock' => 1
    ]);
    $this->redirect('referer');
  }

  function action_unlockPage() {
    if (! $this->pageId)
      throw new Exception('$this->pageId not defined');
    Pages::updateS($this->pageId, [
      'isLock' => 0
    ]);
    $this->redirect('referer');
  }

}