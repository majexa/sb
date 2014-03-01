<?php

class FieldEThemeSelect extends FieldESelect {

  protected function defineOptions() {
    foreach (glob(STM_PATH.'/themes/*') as $folder) {
      $theme = include $folder.'/theme.php';
      $items[] = [
        'title' => $theme['data']['title'],
        'id' => basename($folder)
      ];
    }
    $options[''] = '------------';
    foreach (Arr::sortByOrderKey($items, 'id') as $v) {
      $options['ngn:'.$v['id']] = $v['title']." ({$v['id']})";
    }
    return ['options' => $options];
  }
  
}

class FormThemeUpdate extends Form {

  function __construct() {
    parent::__construct(new Fields([[
      'title' => 'Тема',
      'type' => 'themeSelect',
      'name' => 'theme'
    ]]));
    $this->setElementsData(Config::getVar('theme'));
  }
  
  protected function _update(array $data) {
    StmCore::updateCurrentTheme($data['theme']);
  }
  
}

class CtrlAdminStm extends CtrlAdmin {

  static $properties = [
    'title' => 'Тема',
    'onMenu' => true,
    'order' => 330
  ];
  
  function action_default() {
  	if (($p = StmCore::getCurrentThemeParams()) == false) {
  	  return;
  	}
  	$this->redirect($this->tt->getPath(2).'/editTheme/'.implode('/', $p));
  }
  
  function action_setTheme() {
    SiteConfig::updateSubVar('theme', 'theme',
      $this->req->params[3].':'.$this->req->params[5].':'.$this->req->params[6]);
    $this->redirect($this->tt->getPath(2));
  }
  
  function action_json_changeTheme() {
    $this->json['title'] = 'Изменение темы';
    return $this->jsonFormActionUpdate(new FormThemeUpdate());
  }
  
  function action_deleteFile() {
    die2($this->req->r);
    $dm = new StmThemeDataManager($this->getCurThemeData());
  }
  
  // --------------------------------------------------------------------
  
  function action_menuList() {
    $this->d['items'] = O::get('StmMenuDataSet')->items;
  }
  
  function action_editMenu() {
    $dm = $this->getStmMenuDM();
    if ($dm->requestUpdateCurrent()) {
      $this->redirect();
      return;
    }
    $this->setPageTitle('Редактирование меню');
    $this->d['form'] = $dm->form->html();
  }
  
  function action_updateMenu() {
    $this->getStmMenuDM()->requestUpdateCurrent();
  }
  function action_ajax_updateMenu() {
    $this->ajaxSuccess = $this->getStmMenuDM()->requestUpdateCurrent();
  }
  
  function action_menuNewStep1() {
    $this->setPageTitle('Создание меню. Выбор расположения');
    $this->initLocationForm();
    if ($this->oLF->isSubmittedAndValid()) {
      $this->redirect($this->tt->getPath(2).'/menuNewStep2/'.$this->oLF->data['location']);
      return;
    }
    $this->d['form'] = $this->oLF->html();
    $this->d['tpl'] = 'stm/editMenu';
  }
  
  function action_menuNewStep2() {
    $this->setPageTitle('Создание меню. Выбор типа');
    $oF = new Form(new Fields([
      [
        'title' => 'Тип меню',
        'name' => 'menuType',
        'type' => 'select',
        'required' => true,
        'options' => Arr::get(StmMenuStructures::$structures, 'title', 'KEY')
      ]
    ]), ['submitTitle' => 'Продолжить создание »']);
    if ($oF->isSubmittedAndValid()) {
      $this->redirect($this->tt->getPath(2).'/menuNew/'.$this->req->param(3).'/'.
        $oF->data['menuType']);
      return;
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'stm/form';
  }
  
  function action_menuNew() {
    $oSMDS = new StmMenuDataSource([
      'location' => $this->req->param(3),
      'menuType' => $this->req->param(4)
    ]);
    $this->setPageTitle('Создание нового меню типа «<b>'.
      $oSMDS->structure['title'].'</b>»');
    $oF = new StmForm([
      'oSDS' => $oSMDS,
      'submitTitle' => 'Создать'
    ]);
    if ($oF->update()) {
      $this->redirect($this->tt->getPath(2).'/menuList');
      return;
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'stm/editMenu';
  }

  /**
   * @var Form
   */
  protected $oLF;
  
  protected function initLocationForm() {
    $this->oLF = new Form(new Fields([
      [
        'title' => 'Расположение',
        'name' => 'location',
        'type' => 'select',
        'required' => true,
        'options' => Arr::get(Arr::filterByValue(
          StmLocations::$locations,
        'canEdit', true, true), 'title', 'KEY')
      ]
    ]), ['submitTitle' => 'Продолжить создание »']);
  }
  
  function action_json_themeNewStep1() {
    $this->json['title'] = 'Создание темы. Выбор расположения';
    $this->initLocationForm();
    $this->json['nextFormUrl'] = $this->tt->getPath(2).'/json_themeNewStep2/'.$this->oLF->elementsData['location'];
    return $this->jsonFormActionUpdate($this->oLF);
  }

  function action_json_themeNewStep2() {
    $this->json['title'] = 'Создание темы. Выбор дизайна';
    $oF = new Form(new Fields([
      [
        'title' => 'Дизайн',
        'name' => 'design',
        'type' => 'select',
        'required' => true,
        'options' => Arr::get(O::get('StmDesigns',
          ['siteSet' => SITE_SET])->designs, 'title', 'KEY')
      ]
    ]), ['submitTitle' => 'Продолжить создание »']);
    $this->json['nextFormUrl'] =
      $this->tt->getPath(2).'/json_themeNew/'.$this->req->param(3).'/'.
      SITE_SET.'/'.str_replace(':', '/', $oF->elementsData['design']);
    return $this->jsonFormActionUpdate($oF);
  }
  
  function action_json_themeNew() {
    $dm = new StmThemeDataManager([
      'type' => 'theme',
      'subType' => 'design',
      'location' => $this->req->param(3),
      'siteSet' => $this->req->param(4),
      'design' => $this->req->param(5)
    ]);
    
    $this->setPageTitle(
      'Создание новой темы дизайна «<b>'.$dm->oSD->oSDS->structure['title'].'</b>»');
    if ($dm->requestCreate()) {
      $this->redirect($this->tt->getPath(2));
      return;
    }
    $this->d['form'] = $dm->form->html();
    $this->d['tpl'] = 'stm/editTheme';
  }
  
  protected function getStmThemeDM() {
    return new StmThemeDataManager([
      'type' => 'theme',
      'subType' => 'design',
      'location' => $this->req->param(3),
      'id' => $this->req->param(4),
    ]);
  }
  
  protected function getStmMenuDM() {
    return new StmMenuDataManager([
      'type' => 'menu',
      'subType' => 'menu',
      'location' => $this->req->param(3),
      'id' => $this->req->param(4)
    ]);
  }
  
  function action_editTheme() {
    $dm = $this->getStmThemeDM();
    if ($dm->requestUpdateCurrent()) {
      $this->redirect();
      return;
    }
    $this->setPageTitle('Редактирование темы «<b>'.$dm->getStmData()->data['data']['title'].'</b>» дизайна «<b>'.$dm->getStmData()->getStructure()->str['title'].'</b>»');
    $this->d['form'] = $dm->form->html();
  }
    
  function action_ajax_updateTheme() {
    $this->ajaxSuccess = $this->getStmThemeDM()->requestUpdateCurrent();
  }
  
  function action_deleteTheme() {
    $this->initThemeEditForm();
    $this->oSD->delete();
    $this->redirect($this->tt->getPath(2));
  }
  
  function action_json_themeFancyUpload() {
    LogWriter::v('ddddddd', 1);
  	
    $this->getStmThemeDM()->updateFileCurrent($_FILES['Filedata']['tmp_name'], $this->req->reqNotEmpty('fn'));
  }
  
  function action_json_menuFancyUpload() {
    $this->json['success'] = 
      $this->getStmMenuDM()->updateFileCurrent($_FILES['Filedata']['tmp_name'], $this->req->reqNotEmpty('fn'));
  }
  
}
