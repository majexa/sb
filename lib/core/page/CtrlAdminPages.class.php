<?php

class CtrlAdminPages extends CtrlAdminPagesBase {

  static $properties = [
    'title'  => 'Разделы сайта',
    'onMenu' => true,
    'order'  => 5
  ];

  protected function init() {
    parent::init();
    if (!$this->folder and $this->action == $this->defaultAction) {
      if (!empty($this->page['controller']) and
        PageControllersCore::isEditebleContent($this->page['controller'])
      ) {
        //$this->redirect($this->tt->getPath(3).'/editContent');
        //return;
        //$this->addSubController($sub);
      }
      else {
        $this->redirect($this->tt->getPath(3).'/editPage');
        return;
      }
    }
  }

  function addSubControllers() {
    if (in_array($this->getParamAction(), ['editContent', 'json_getItems', 'xls'])) {
      $this->allowRequestAction = false;
      $class = AdminPagesCore::getEditContentSubController($this->page['controller']);
      if ($class) $this->addSubController(O::get($class, $this));
    }
  }

  function action_newPage() {
    $this->d['controllers'] = $this->getPageControllerOptions();
    $this->d['tpl'] = 'pages/editPage';
    $this->d['postAction'] = 'createPage';
    $this->setPageTitle(LANG_CREATE_NEW_SECTION);
  }

  function action_ajax_delete() {
    DbModelCore::delete('pages', $this->req->r['id']);
  }

  function action_ajax_activate() {
    DbModelPages::getTree()->updatePropertyWithChildren($this->req->r['id'], 'active', 1);
  }

  function action_ajax_deactivate() {
    DbModelPages::getTree()->updatePropertyWithChildren($this->req->r['id'], 'active', 0);
  }

  function action_ajax_onMenu() {
    DbModelCore::update('pages', $this->req->r['id'], ['onMenu' => $this->req->r['onMenu']]);
  }

  function action_json_editControllerSettings() {
    if (empty($this->page['controller'])) throw new EmptyException("\$this->page['controller']");
    $oF = new PageControllerSettingsForm($this->page);
    if ($oF->update()) {
      if (!empty($this->page['controller'])) {
        // Дополнительные диалоги, после схоранения св-в контроллера
        if (($dialogs = PageControllersCore::getPropObj($this->page['controller'])->getAfterSaveDialogs($oF)) !== false) {
          $this->json['dialogs'] = $dialogs;
          return;
        }
      }
      return;
    }
    $this->json['title'] = LANG_CONTROLLER_OPTIONS.' <b>«'.PageControllersCore::getPropObj($this->page['controller'])->title.'»</b> '.LANG_SECTION.' <b>«'.$this->d['page']['title'].'»</b>';
    return $oF;
  }

  function action_default() {
    $this->d['parent'] = DbModelCore::get('pages', $this->parentId);
    $this->d['items'] = DbModelPages::getTree()->getChildren($this->pageId);
    $this->setPageTitle($this->d['parent']['title']);
  }

  function action_ajax_reload() {
    $this->d['items'] = DbModelPages::getTree()->getChildren($this->pageId);
    $this->ajaxOutput = $this->tt->getTpl('admin/modules/pages/itemsTable', $this->d);
  }

  protected function initPathDd() {
    if (!isset($this->pageCtrl)) throw new Exception('$this->pageCtrl not defined');
    if (!isset($this->pageCtrl->masterItem)) return;
    if (empty($this->pageCtrl->masterItem['title'])) throw new Exception("\$this->pageCtrl->masterItem['title'] is empty.");
    $this->d['path'][count($this->d['path']) - 1] = [
      'title' => $this->pageCtrl->masterItem['title'],
      'name'  => 'page_edit',
      'link'  => $this->tt->getPath()
    ];
  }

  function action_json_editItemSystemDates() {
    $this->json['title'] = 'Редактирование дат';
    $itemId = $this->req->param(4);
    $im = new DdItemSystemDatesManager(DbModelCore::get('pages', $this->page['id'])->r['strName'], $itemId);
    if ($im->requestUpdate($itemId)) return;
    return $im->form;
  }

  /**
   * 1) Создает поле 'oid', если его нет
   * 2) Выставляет в настройках раздела поле сортировки
   *
   */
  function action_setOidPageOrder() {
    if (!$strName = $this->page['strName']) throw new Exception('$strName not defined');
    $oF = O::get('DdFields', $strName);
    if (!$oF->getField('oid')) {
      $oF->create([
        'title'  => LANG_ORDER_NUM,
        'name'   => 'oid',
        'type'   => 'num',
        'system' => true,
        'oid'    => 300
      ]);
    }
    DbModelPages::addSettings($this->page['id'], ['order' => 'oid']);
    $this->redirect('referer');
  }

  /**
   * 1) Удаляет поле 'oid', если оно есть
   * 2) Обнуляет поле сортировки
   */
  function action_resetOidPageOrder() {
    if (!$strName = $this->page['strName']) throw new Exception('$strName not defined');
    $oF = O::get('DdFields', $strName);
    $field = $oF->getDataByName('oid');
    if ($field['id']) $oF->delete($field['id']);
    DbModelPages::addSettings($this->page['id'], ['order' => '']);
    $this->redirect('referer');
  }

  function action_setRatingOn() {
    $oRI = new RatingInstaller();
    $oRI->install($this->page['id']);
    $this->redirect('referer');
  }

  function action_setRatingOff() {
    $oRI = new RatingInstaller();
    $oRI->uninstall($this->page['id']);
    $this->redirect('referer');
  }

  protected function getControllerRequiredFieldsHtml($controller) {
    $oF = new DdForm(new PageControllerSettingsFields($controller), null);
    $oF->setNameArray('settings');
    if (!$oF->fields->getRequired()) return '';
    $oF->onlyRequired = true;
    $oF->disableSubmit = true;
    $oF->disableFormTag = true;
    if ($this->pageId) $oF->setElementsData(DbModelCore::get('pages', $this->pageId)->r['initSettings']);
    return $oF->html();
  }

  function action_ajax_controllerRequiredFields() {
    if (empty($this->req->r['controller'])) return;
    $this->ajaxOutput = $this->getControllerRequiredFieldsHtml($this->req->rq('controller'));
  }

  function action_json_newModulePage() {
    return $this->jsonFormActionUpdate(new NewModulePageForm($this->pageId));
  }

  function action_json_newModulePageSimple() {
    return $this->jsonFormActionUpdate(new NewModulePageSimpleForm($this->pageId));
  }

  function action_json_newPage() {
    return $this->jsonFormActionUpdate(new NewPageForm($this->pageId));
  }

  function action_json_controllerRequiredFields() {
    $this->json = O::get('PageControllerSettingsFields', $this->req->rq('controller'))->getFields();
  }

  function action_json_editPageProp() {
    return $this->jsonFormActionUpdate(new EditPagePropForm($this->pageId, $this->god));
  }

  function action_json_editPagePropSimple() {
    return $this->jsonFormActionUpdate(new EditPagePropSimpleForm($this->pageId, $this->god));
  }

  function action_json_getTree() {
    $this->json = (new MifTreePages)->getTree();
  }

  function action_ajax_reorder() {
    DbShift::items($this->req->rq('ids'), 'pages');
  }

  function action_ajax_move() {
    DbModelPages::move($this->req->rq('id'), $this->req->rq('toId'), $this->req->rq('where'));
  }

  protected function extendTplDataTagFilter() {
    if (empty($this->d['pcd']['settings']['tagField'])) return;
    $tagField = $this->d['pcd']['settings']['tagField'];
    $tagOptions = ['' => '- без фильтра -'];
    if (isset($this->d['pcd']['tags'][$tagField])) {
      $isTree = strstr($this->d['pcd']['fields'][$tagField]['type'], 'Tree');
      if ($isTree) {
        $this->d['pcd']['tags'][$tagField] = DdTagsHtml::treeToList($this->d['pcd']['tags'][$tagField]);
      }
      foreach ($this->d['pcd']['tags'][$tagField] as $v) {
        // Формируем список тегов
        $isTree ? // Для древовидного типа
          $tagOptions[$v['id']] = str_repeat('&bull; ', $v['depth']).$v['title']." ({$v['cnt']})" : // Для линейного типа
          $tagOptions[$v['name']] = $v['title']." ({$v['cnt']})";
      }
    }
    if (isset($this->d['pcd']['tagsSelected'])) {
      if (($tagSelected = Arr::first($this->d['pcd']['tagsSelected'])) !== false) {
        $this->d['path'][] = [
          'title' => $tagSelected['title'],
          'link'  => $this->tt->getPath()
        ];
      }
    }
    $this->d['filter'] = [
      'name'    => $tagField,
      'param'   => 't2',
      'title'   => $this->d['pcd']['fields'][$tagField]['title'],
      'options' => $tagOptions
    ];
    $this->d['filter']['selected'] = $this->d['pcd']['filters']['t2'][1];
  }

  protected function extendTplDataDdItemsFilter() {
    // Получаем данные для фильтра по полю slave-полу типа ddSlaveItemsSelect
    if (isset($this->d['pageCtrl']) and $this->d['pageCtrl']->masterPageId) {
      $oI = new DdItemsPage($this->d['pageCtrl']->masterPageId);
      $filterOptions = ['' => '- без фильтра -'];
      $filterOptions += Arr::get($oI->getItems(), 'title', 'id');
      $this->d['filter'] = [
        'name'    => $this->d['pageCtrl']->masterField['name'],
        'param'   => 'v',
        'title'   => $this->d['pageCtrl']->masterField['title'],
        'options' => $filterOptions
      ];
      $this->d['filter']['selected'] = $this->d['pcd']['filters']['v'][1];
    }
  }

  protected function extendTplData() {
    parent::extendTplData();
    $this->extendTplDataTagFilter();
    $this->extendTplDataDdItemsFilter();
    if (!empty($this->d['page']['module'])) $this->extendMainContentCssClass('pageModule_'.$this->d['page']['module']);
  }

}
