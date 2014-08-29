<?php

abstract class CtrlPageDdOld extends CtrlPage {

  /**
   * @var DdItemsManagerPage
   */
  public $im;

  /**
   * Имя структуры
   *
   * @var string
   */
  public $strName;


  /**
   * ID текущей записи
   *
   * @var integer
   */
  public $itemId;

  /**
   * Данные текущей записи
   *
   * @var array
   */
  public $itemData;

  /**
   * Данные пользователя текущей записи
   *
   * @var array
   */
  public $itemUser;

  public $requiredSettings = ['strName'];

  /**
   * Тип редиректа при успешном выполнении экшена:
   * referer/self/completePage
   *
   * @var string
   */
  public $completeRedirectType;

  /**
   * Экшены, имеющие страничку "complete"
   *
   * @var array
   */
  public $completeActions = [
    'new', 'edit', 'delete'
  ];

  public $isParentSubAction = false;

  public $useDefaultTplFolder = true;

  /**
   * Имя структуры мастер-раздела
   *
   * @var integer
   */
  public $masterStrName;

  /**
   * ID мастер-раздела
   *
   * @var integer
   */
  public $masterPageId;

  /**
   * Имя поля мастер-раздела
   *
   * @var string
   */
  public $masterField;

  protected function init() {
    $this->strName = $this->page['strName'];
    $this->itemId = isset($this->req->r['itemId']) ? (int)$this->req->r['itemId'] : 0;
    if (!$this->itemId) $this->itemId = isset($this->req->params[1]) ? (int)$this->req->params[1] : 0;
    // Перед инициализацией класса CtrlPage, в котором инициализируется объект
    // комментариев, для этого объекта нужно определить $this->id2
    $this->id2 = $this->itemId;
    parent::init();
  }

  protected function getItemsManagerOptions() {
    throw new Exception('depricated');
    return [
      'itemId'        => $this->itemId,
      'fieldsOptions' => [
        'getDisallowed' => $this->adminMode
      ]
    ];
  }

  protected function initItemsManager() {
    $this->im = DdCore::getItemsManager($this->page['id'], $this->getItemsManagerOptions());
    $this->im->items->setPriv($this->priv);
    $this->im->form->ctrl = $this;
    if ($this->page['module']) if (($paths = SbHook::paths('dd/initFields', $this->page['module'])) !== false) foreach ($paths as $path) include $path;
    $this->d['fields'] = $this->im->form->fields->getFields();
    if ($this->userGroup) $this->im->createData = ['userGroupId' => $this->userGroup['id']];
    if (!Misc::hasSuffix('edit', $this->action)) {
      if (!empty($this->page['settings']['createBtnTitle'])) $this->im->form->options['submitTitle'] = $this->page['settings']['createBtnTitle'];
      else
        $this->im->form->options['submitTitle'] = 'Добавить';
    }
  }

  function updateCommentsDate() {
    if (!$this->itemId) throw new Exception('$this->itemId not defined');
    $this->im->items->updateField($this->itemId, 'commentsUpdate', dbCurTime());
  }

  protected function setFormTpl($form) {
    if (empty($this->page['settings']['formTpl'])) return;
    if (!$tpls = Config::getVar('formTpl.'.$this->page['settings']['formTpl'], true)) throw new Exception('Config var "formTpl.'.$this->page['settings']['formTpl'].'" with form '.'templates not exists.');
    foreach ($tpls as $k => $v) {
      $form->templates[$k] = $v;
    }
  }

  function setItemData() {
    if (isset($this->itemData)) return $this->itemData;
    if (!($this->itemData = $this->im->items->getItemF($this->itemId))) return false;
    $this->d['item'] = $this->itemData;
    return $this->itemData;
  }

  function action_new() {
    $this->d['tpl'] = 'edit';
    if (($id = $this->im->requestCreate(isset($this->req->r['default']) ? $this->req->r['default'] : [])) !== false) {
      $this->itemId = $id;
      $this->completeRedirect();
      return $id;
    }
    else {
      $this->d['form'] = $this->im->form->html();
      return false;
    }
  }

  /**
   * Производит редактирование
   * Возвращает true при удачном апдейте
   *
   * @return bool
   */
  function action_edit() {
    if (empty($this->itemId)) {
      $this->error404('Редактирование невозможно');
      return false;
    }
    $this->im->form->setActionFieldValue('edit');
    $this->d['itemId'] = $this->itemId;
    $this->d['tpl'] = 'edit';
    if ($this->im->requestUpdate($this->itemId)) {
      $this->completeRedirect();
      return true;
    }
    else {
      $this->initDeleteFileUrl();
      $this->d['form'] = $this->im->form->html();
    }
    return false;
  }

  protected function initDeleteFileUrl() {
    $this->im->form->options['deleteFileUrl'] = $this->tt->getPath(1).'/'.$this->itemId.'/?a=deleteFile';
  }

  function action_json_new() {
    if ($this->im->requestCreate(isset($this->req->r['default']) ? $this->req->r['default'] : [])) {
      $this->json = $this->im->data;
      return;
    }
    return $this->im->form;
  }

  function action_json_edit() {
    $this->initDeleteFileUrl();
    if ($this->im->requestUpdate($this->id2)) {
      $this->json = $this->im->data;
      return;
    }
    return $this->im->form;
  }

  function action_updateDirect() {
    $this->action_ajax_updateDirect();
    $this->redirect();
  }

  function action_ajax_updateDirect() {
    if (!isset($this->req->r['k'])) throw new Exception("\$this->req->r['k'] not defined");
    $k = mysql_real_escape_string($this->req->r['k']);
    db()->query("UPDATE dd_i_{$this->strName} SET $k=? WHERE id=?d", $this->req->r['v'], $this->req->r['itemId']);
  }

  function action_complete() {
    if (!isset($this->req->r['completeAction'])) throw new Exception("\$this->req->r['completeAction'] not defined");
    $completeAction = $this->req->r['completeAction'];
    if ($completeAction != 'delete') {
      if (!$this->itemId) {
        $this->error404();
        return;
      }
      $this->d['moders'] = Moder::getModers($this->page['id']);
    }
    if ($this->tt->exists('complete.'.$completeAction)) {
      $this->d['tpl'] = 'complete.'.$completeAction;
    }
    else {
      $this->d['tpl'] = 'complete';
    }
  }

  function action_delete() {
    $this->im->delete($this->itemId);
    $this->completeRedirect();
  }

  function action_ajax_delete() {
    $this->im->delete($this->itemId);
  }

  function action_ajax_copy() {
    $this->im->items->copy($this->itemId);
  }

  protected $disableCompleteRedirect = false;

  function completeRedirect() {
    if ($this->disableCompleteRedirect) return;
    if (!isset($this->completeRedirectType) and
      !empty($this->page['settings']['completeRedirectType'])
    ) {
      $this->completeRedirectType = $this->page['settings']['completeRedirectType'];
    }
    if ($this->completeRedirectType == 'referer') {
      // referer
      $this->redirect('referer');
    }
    elseif ($this->completeRedirectType == 'referer_item') {
      // referer_item
      if ($this->action == 'edit' or $this->action == 'new') {
        $this->redirect($this->tt->getPath(1).'/'.$this->itemId);
      }
      else {
        $this->redirect('referer');
      }
    }
    elseif ($this->completeRedirectType == 'self') {
      // self
      $this->redirect();
    }
    elseif ($this->completeRedirectType == 'edit') {
      // edit
      $this->redirect($this->tt->getPath(1).((!isset($this->static_id) and isset($this->itemId)) ? '/'.$this->itemId : '').'?a=edit');
    }
    elseif ($this->completeRedirectType == 'fullpath') {
      // fullpath
      $this->redirect('fullpath');
    }
    else {
      // complete
      $this->redirect($this->tt->getPath().'?a=complete&completeAction='.$this->action.($this->itemId ? '&itemId='.$this->itemId : ''));
    }
  }

  function action_ajax_deleteFile() {
    $this->deleteFile();
  }

  function action_deleteFile() {
    $this->deleteFile();
    $this->completeRedirect();
  }

  protected function deleteFile() {
    $this->im->deleteFile($this->itemId, $this->req->rq('fieldName'));
  }

  function action_ajax_import() {
    DdImporter::import($this->strName, $this->page['id'], $_POST['importData']);
  }

  function action_changeUserForm() {
    $this->d['tpl'] = 'dd/changeUser';
  }

  function action_changeUser() {
    if (!$this->itemId) return;
    $this->im->items->update($this->itemId, ['userId' => $_POST['userId']]);
    $this->redirect();
  }

  function action_ajax_subscribeNewComments() {
    if (!$this->userId) throw new Exception('No user ID');
    if (!$this->itemId) throw new Exception('No item ID');
    Notify_SubscribeItems::update($this->userId, 'comments_newMsgs', $this->page['id'], $this->itemId);
  }

  function action_ajax_unsubscribeNewComments() {
    if (!$this->userId) throw new Exception('No user ID');
    if (!$this->itemId) throw new Exception('No item ID');
    Notify_SubscribeItems::delete($this->userId, 'comments_newMsgs', $this->page['id'], $this->itemId);
  }

  function action_ajax_subscribeNewItems() {
    if (!$this->userId) throw new Exception('No user ID');
    Notify_SubscribePages::update($this->userId, 'items_new', $this->page['id']);
  }

  function action_ajax_unsubscribeNewItems() {
    if (!$this->userId) throw new Exception('No user ID');
    Notify_SubscribePages::delete($this->userId, 'items_new', $this->page['id']);
  }

  protected function afterAction() {
    parent::afterAction();
    if (($l = $this->getPageLabel()) !== null) $this->setPageTitle($this->d['pageTitle'].$l);
  }

  protected function initSubPriv() {
    $this->setPriv('sub_view', true);
  }

  protected function click() {
    $this->im->items->click($this->itemId);
  }

  protected function rate() {
    $this->im->items->click($this->itemId);
  }

  protected function initMeta() {
    parent::initMeta();
    if ($this->action == 'showItem') {
      if (($r = db()->selectRow('SELECT * FROM dd_meta WHERE id=?d AND strName=?', $this->itemId, $this->page['strName']))) {
        $this->d['pageMeta'] = $r;
      }
      else {
        if (isset($this->itemData['text'])) $this->d['pageMeta']['description'] = Misc::cut($this->itemData['text'], 255);
      }
    }
  }

  protected function prepareTplPath() {
    parent::prepareTplPath();
    $tpl = $this->d['tpl'];
    if ($this->useDefaultTplFolder and
      $this->tt->exists($this->tplFolder.'/'.$this->strName.'/'.$tpl)
    ) {
      $this->d['tpl'] = $this->tplFolder.'/'.$this->strName.'/'.$tpl;
    }
    elseif (!empty($this->page['settings']['tplName']) and
      $this->tt->exists($this->tplFolder.'/'.$this->page['settings']['tplName'].'/'.$tpl)
    ) {
      // Если задан каталог с шаблонами и необходимый фаблон в нем существует
      $this->d['tpl'] = $this->tplFolder.'/'.$this->page['settings']['tplName'].'/'.$tpl;
    }
    elseif ($this->tt->exists($this->tplFolder.'/'.$tpl)) {
      $this->d['tpl'] = $this->tplFolder.'/'.$tpl;
    }
  }

  function processDynamicBlockModels(array &$blockModels) {
    if (in_array($this->action, ['new', 'edit'])) $blockModels = [];
    /*
    $blockModels[] = new DbModelVirtual(array(
      'type' => 'text',
      'colN' => 1,
      'settings' => array(
        'text' => 'System edit message'
      )
    ));
    */
  }


}
