<?php

abstract class SubCtrlMsgs extends SubCtrl {

  public $id1;

  public $id2;

  /**
   * @var Msgs
   */
  public $msgs;

  public $redirect;

  public $editTime;

  public $msgId;

  public $msgData;

  public $anonym = false;

  /**
   * @var FormSpamBotBlocker
   */
  public $oFSBB;

  function __construct(CtrlCommon $ctrl, $id1, $id2) {
    parent::__construct($ctrl);
    Misc::checkEmpty($id1);
    Misc::checkEmpty($id2);
    $this->id1 = $id1;
    $this->id2 = $id2;
  }

  function init() {
    $this->editTime = 30 * 30 * 0.5;
    $this->msgId = !empty($this->ctrl->req->r['id']) ? $this->ctrl->req->r['id'] : null;
    $this->d['tpl'] = 'common/msgs';
    $this->setAnonym();
    $this->initMsgs();
    $this->setLevel();
    //$this->d['priv'] = $this->ctrl->priv;
    $this->d['ctrl'] = $this->ctrl;
  }

  protected function setAnonym() {
    if (!empty($this->ctrl->settings['allowAnonym']) and !Auth::get('id')) {
      $this->d['anonym'] = $this->anonym = true;
    }
  }

  protected function setLevel() {
    $this->d['level'] = db()->selectCell('SELECT level FROM level_users WHERE userId=?d', Auth::get('id'));
  }

  protected function getMsgData() {
    if (!$this->msgId) return false;
    $this->msgData = $this->msgs->getMsg($this->msgId);
    return $this->msgData;
  }

  abstract protected function initMsgs();

  function action_default() {
    $this->d['items'] = $this->msgs->getMsgsPaged();
    $this->d['pagination']['pNums'] = $this->msgs->pNums;
    //$this->setSpamBotBlocker(); // Инициализируем анти-спам систему
    //$this->d['fsbbTags'] = $this->oFSBB->makeTags(); // Получаем скрытые анти-спам поля
    $this->d['subscribed'] = $this->msgs->isSubscribed(Auth::get('id'));
  }

  function action_ajax_getText() {
    if (!$msgData = $this->getMsgData()) return;
    $this->ctrl->ajaxOutput = $msgData['text'];
  }

  function action_ajax_update() {
    if (!$msgId = (int)$this->ctrl->req->r['id'] or !$msgData = $this->msgs->getMsg($msgId)) return;
    $this->msgs->update($this->ctrl->req->r['id'], $this->ctrl->req->r['text']);
    $msgData = $this->msgs->getMsg($msgId);
    $this->ctrl->ajaxOutput = $msgData['text_f'];
  }

  function action_ajax_activate() {
    $this->msgs->activate($this->ctrl->req->r['id']);
    $this->ctrl->ajaxSuccess = true;
  }

  function action_ajax_deactivate() {
    $this->msgs->deactivate($this->ctrl->req->r['id']);
    $this->ctrl->ajaxSuccess = true;
  }

  function action_ajax_delete() {
    $this->msgs->delete($this->ctrl->req->r['id']);
    $this->ctrl->ajaxSuccess = true;
  }

  function action_json_refrash() {
  }

  function action_json_create() {
    if (($id = $this->create()) === false) {
      $this->ctrl->json['error'] = $this->error;
      return;
    }
    $msg = $this->msgs->getMsgF($id);
    $this->ctrl->json['msgsIds'][] = $id;
    $this->ctrl->json['msgsHtml'][] = Tt()->getTpl('common/msg', $msg);
  }

  function action_create() {
    if ($this->anonym and !$this->ctrl->req->r['nick']) {
      $this->d['errors'][] = 'Введите ник';
      $this->ctrl->action_default();
      return;
    }
    $this->create();
    $this->redirect();
  }

  protected $error;

  protected function create() {
    if (trim($this->ctrl->req->r['text']) == '') {
      $this->error = 'Текст пустой';
      return false;
    }
    try {
      $d = $this->ctrl->req->r;
      $d['userId'] = Auth::get('id');
      $d['userGroupId'] = $this->ctrl->userGroup ? $this->ctrl->userGroup['id'] : 0;
      $this->msgs->create($d);
    } catch (NgnValidError $e) {
      $this->error = $e->getMessage();
      return false;
    }
    if (method_exists($this->ctrl, 'updateCommentsDate')) $this->ctrl->updateCommentsDate();
    return $id;
  }

  function action_subscribe() {
    $this->msgs->subscribe(Auth::get('id'));
    $this->redirect();
  }

  function action_unsubscribe() {
    $this->msgs->unsubscribe(Auth::get('id'));
    $this->redirect();
  }

  /**
   * Inline Images Upload
   */
  function action_iiUpload() {
    $this->d['mainTpl'] = 'popups/uploadImage';
    $this->d['file'] = $this->msgs->iiUpload($_FILES['image']);
  }

  protected function setSpamBotBlockerAction() {
    $this->setSpamBotBlocker();
    if ($_POST) $param = $_POST;
    elseif ($_GET) $param = $_GET;
    if (!$param) Err::warning("This script requires some POST or GET parameters");
    $this->d['nospam'] = $this->oFSBB->checkTags($param);
    return $this->d['nospam'] ? true : false;
  }

  protected function setSpamBotBlocker() {
    return;
    $this->oFSBB = new FormSpamBotBlocker();
    $this->oFSBB->setTimeWindow(2, 30); // set the min and max time in seconds for a form to be submitted
    $this->oFSBB->setTrap(true, 'mail', 'checkthesun'); // called here, because it has been called on the form page as well (same trap name!)
    $this->oFSBB->hasSession = false;
    // $submissions = $_SESSION[$blocker->sesName];  
  }

}
