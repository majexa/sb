<?php

abstract class Permissions extends ArrayAccesseble {
}

trait PermissionCtrl123 {

  /**
   * Массив, в котором каждая привилегия определяет те экшены (без layout-префиксов),
   * которые она разрешает
   *
   * @var   array
   */
  protected $actionByPermission = [
    'view'       => ['default'],
    'moder'      => ['edit', 'new', 'moveForm', 'move', 'delete', 'activate', 'deactivate', 'publish'],
    'edit'       => ['edit', 'update', 'delete', 'move', 'activate', 'deactivate', 'deleteFile'],
    'editOnly'   => ['edit', 'delete'],
    'admin'      => ['updateDirect', 'deleteGroup', 'deleteFile'],
    'create'     => ['new'],
    'sub_edit'   => ['sub_edit', 'sub_update', 'sub_delete', 'sub_activate', 'sub_deactivate'],
    'sub_create' => ['sub_create']
  ];

  protected $permissionByAction;

  protected function initActionsByPermission() {
    foreach ($this->actionByPermission as $permission => $actions) {
      foreach ($actions as $action) {
        $this->permissionByAction[$action] = $permission;
      }
    }
  }

  protected function allowedActions() {
    //foreach ($this->)
    //foreach ($this->permissions() as $permission)
    foreach (array_keys($this->priv->r) as $priv) {
      if (isset($this->actionByPriv[$priv])) {
        foreach ($this->actionByPriv[$priv] as $action) $this->allowedActions[] = $action;
      }
    }
  }

  protected function initAllowedActions() {
    $this->d['allowedActions'] = $this->allowedActions();
  }

  /**
   * Определяет возможность редактирования текущим пользователем данного раздела/записи
   * см. дальнейшую реализацию метода в наследуемых классах
   */
  protected function initPriv() {
    $this->initActionsByPermission();
    $this->d['privAuth'] = $this->priv->getAuthPriv();
    $this->d['priv'] = $this->priv;
    $this->initAllowedActions();
  }

  /**
   * @return Permissions
   */
  abstract protected function permissions();

  /**
   * Разрешить ли данный экшен
   *
   * @param   string    Имя экшена
   * @return  bool
   */
  protected function allowAction($action) {
    $action = $this->clearActionPrefixes($action);
    if (!isset($this->priv)) return true;
    // Если для экшена нет привилегий, значит по умолчанию он разрешен
    if (!isset($this->privByAction[$action])) return true;
    return in_array($action, $this->allowedActions);
  }
  protected $allowAuthorized = false;
  protected $allowAuthorizedActions = [];

  protected function action() {
    if (!isset($this->priv)) {
      parent::action();
      return;
    }
    if (empty($this->priv['view'])) {
      $this->error404();
      return;
    }
    if ($this->allowAuthorized and !Auth::get('id')) {
      $this->d['tpl'] = 'denialAuthorize';
      return;
    }
    elseif ($this->allowAuthorizedActions and in_array($this->action, $this->allowAuthorizedActions)) {
      $this->d['tpl'] = 'denialAuthorize';
      return;
    }
    elseif (!$this->allowAction($this->action) or $this->req['editNotAllowed']) {
      throw new AccessDenied;
      return;
    }
    parent::action();
  }

  /**
   * Каталог с шаблонами для админки
   *
   * @var string
   */
  public $adminTplFolder;

  /**
   * Флаг определяет, что контроллер был вызван из админки
   *
   * @var bool
   */
  public $adminMode = false;

}