<?php

class CtrlPageDdItems extends CtrlPageDd {
  use DdParamFilterCtrl;

  protected $defaultAction = 'list';

  public $order;

  public $editTime = 9999999999;

  public $adminTplFolder = 'items';

  //////////////////// UserItems ////////////////////

  /**
   * Флаг определяет необходимость жесткого использования фильтров
   *
   * @var bool
   */
  public $strictFilters = true;

  protected function initAction() {
    if ($this->isListParams()) $this->defaultAction = 'list';
    parent::initAction();
  }

  protected function init() {
    if ($this->page->getS('ownerMode') == 'userGroup' and !O::get('SiteRequest')->getSubdomain()) throw new Exception('Controller must work only with subdomains');
    $this->d['isItemsController'] = true;
    if (!empty($this->page['settings']['editTime'])) $this->editTime = $this->page['settings']['editTime'];
    if (!isset($this->itemsCacheEnabled)) if (Config::getVarVar('dd', 'forceCache')) $this->itemsCacheEnabled = false;
    else
      $this->itemsCacheEnabled = empty($this->page['settings']['disableItemsCache']);
    /**
     * @todo необходимо сделать ревизию и убрать из init()
     * те функции, что вызываются только для получания одной или нескольких записей
     */
    if (!isset($this->action)) {
      if (isset($this->tag)) $this->setAction('tag');
      elseif (isset($this->tags)) $this->setAction('tags');
    }
    ////////////////////////////////////////////////////
    parent::init();
    $this->d['ddType'] = 'ddObjects'; // Необходимо для шаблона модераторского-меню
    $this->d['tpl'] = 'default';
    // Это не просто так тут. оно не может быть в методе initAction(), потому что
    // $this->itemId определяется только в init()
    if ($this->action == $this->defaultAction and $this->itemId) $this->setAction('showItem');
    // Порядок вывода
    if (isset($this->req->params[1]) and $this->req->params[1] == 'o') {
      $allowedOrderFields = [
        'dateCreate',
        'dateUpdate',
        'datePublish',
        'commentsUpdate',
        'title',
        'commentsCount'
      ];
      if (in_array($this->req->params[2], $allowedOrderFields)) {
        $this->order = $this->req->params[2].($this->req->params[3] == 'a' ? ' ASC' : ' DESC');
      }
    }
    if (!isset($this->order) and !empty($this->page['settings']['order'])) $this->setOrder($this->page['settings']['order']);
    // преобразование пути происходив в экшене. так что нужно заменить последний элемент пути до выполнения экшенов
    if ($this->userGroup) $this->d['pathData'][0] = [
      'title' => $this->userGroup['title'],
      'link'  => SiteRequest::url($this->userGroup['name']).'/'.DbModelCore::get('pages', 'userGroupHome', 'module')->r['path']
    ];
    $this->initItemsManager();
    $this->initPagination();
    $this->initTagsTplCommonData();
    $this->initDateRange2RangeFilter();
    $this->initOrderByParams();
    if ($this->itemId and !empty($this->page['settings']['comments'])) {
      $this->addSubController(new SubPaComments($this, $this->page['id'], $this->itemId));
    }
  }

  protected function paramFilterItems() {
    return $this->im->items;
  }

  protected function addSubControllers() {
    if ($this->page['slave']) $this->addSubController(new SubPaDdSlave($this));
  }

  protected function initDateRange2RangeFilter() {
    if (empty($this->page['settings']['dateFieldBegin']) or
      empty($this->page['settings']['dateFieldEnd'])
    ) return;
    $m = [];
    if (preg_match('/d2\.(.*)-(.*)/', $this->req->params[1], $m)) {
      $from = date_format(new DateTime($m[1]), 'Y-m-d');
      $to = date_format(new DateTime($m[2]), 'Y-m-d');
    }
    else {
      $from = date('Y-m-d');
      $to = '9000-01-01';
    }
    $this->im->items->cond->addRange2RangeFilter($this->page['settings']['dateFieldBegin'], $this->page['settings']['dateFieldEnd'], $from, $to);
  }

  /**
   * Определяет сортировку
   *
   * @param string Строка сортировки. Пример: 'title DESC'
   */
  protected function setOrder($order) {
    $field = preg_replace('/([a-zA-Z]*)\s+(ASC|DESC|)/', '$1', $order);
    if (!$field) {
      throw new Exception('$field not defined');
      return;
    }
    if (!$this->strName) return;
    $this->order = $order;
    $this->d['orderField'] = $field;
  }

  private $shortOrderParams = [
    'create'   => 'dateCreate',
    'update'   => 'dateUpdate',
    'publish'  => 'datePublish',
    'comments' => 'commentsUpdate'
  ];

  public $orderField;

  protected function initOrderByParams() {
    // Пробегаемся по всем параметрам и смотри нет ли чего похожего на сортировку
    foreach ($this->req->params as $param) {
      if (preg_match('/^(oa|o)\.([a-zA-Z]+)/', $param, $m)) {
        if (isset($this->shortOrderParams[$m[2]])) {
          $field = $this->shortOrderParams[$m[2]];
        }
        else {
          $field = $m[2];
        }
        $this->orderField = $field;
        if ($m[1] == 'o') {
          // Order DESC. Пример: o=dateCreate
          $this->setOrder($field.' DESC');
        }
        else {
          // Order ASC. Пример: oa=dateCreate
          $this->setOrder($field.' ASC');
        }
        // Если присутствует условие для сортировки, значит нужно вызывать экшн 'list'
        $this->setActionIfNrequestAction('list');
      }
    }
  }

  protected function initPagination() {
    if (!empty($this->page['settings']['n'])) {
      $this->im->items->setN($this->page['settings']['n']);
    }
    else {
      $this->im->items->setN(Config::getSubVar('dd', 'itemsN'));
    }
  }

  /*
  protected function afterInit() {
    parent::afterInit();
    $this->setPrivAfterInit();
  }
  */

  /**
   * Создатель текущей страницы
   *
   * @var DbModelUsers
   */
  protected $pageUser;

  protected function afterAction() {
    if ($this->hasOutput) {
      $this->__call('initPath');
      if (empty($this->d['pageTitle']) and !empty($this->page['settings']['itemTitle'])) $this->setPageTitle($this->page['settings']['itemTitle']);
      if (isset($this->d['itemUser'])) $this->pageUser = $this->d['itemUser'];
      elseif (isset($this->d['itemsUser'])) $this->pageUser = $this->d['itemsUser'];
    }
    parent::afterAction();
  }

  function initPath() {
    $this->__call('initAuthorPath');
    $this->initItemPath();
  }

  protected function initAuthorPath() {
    // Если необходимо добавлять пользователя в путь к странице
    if ($this->page->getS('ownerMode') != 'author') return;
    if (!empty($this->d['itemUser'])) {
      $this->setPathData($this->tt->getPath(1).'/u.'.$this->d['itemUser']['id'], $this->d['itemUser']['login']);
    }
  }

  /**
   * Добавляет привилегии для текущего пользователя
   */
  protected function setPrivAfterInit() {
    if (!$this->itemId) return;
    if (!($item = $this->setItemData())) return;
    if (Privileges::extendItemPriv($item, Auth::get('id'), $this->editTime)) {
      // Если включено премодерирование, ограничиваем данную привилегию до "editOnly"
      !empty($this->page['settings']['premoder']) ? $this->priv['editOnly'] = 1 : $this->priv['edit'] = 1;
    }
    $this->initAllowedActions();
  }

  protected function showFormOnDefault() {
    $this->im->form->setActionFieldValue('new');
    $this->d['form'] = $this->im->form->html();
  }

  /**
   * @return DdXls
   */
  protected function getLongJob() {
    $this->initFilterByParams();
    return O::gett('DdXls', $this->im->items);
  }

  // ----------

  function action_list() {
    $this->initItems();
    // Вывод формы по умолчанию
    if (!empty($this->page['settings']['showFormOnDefault'])) $this->showFormOnDefault();
    // "Необходимо присутствие фильтра по пользователю" Актуально только для дефолтового экшена вывода записей
    // В каких случаях это необходимо? в случае записей пользователя
    if ($this->strictFilters and
      isset($this->page['settings']['userFilterRequired']) and
      $this->page['settings']['userFilterRequired'] and
      $this->req->params[$this->paramFilterN()] != 'u'
    ) {
      // Если необходимо присутствие фильтра по пользователю, а он не задан
      $this->error404('Необходимо присутствие фильтра по пользователю');
      return;
    }
    // Если существуют несколько разделов этой структуры, мы можем перемещать
    $this->d['tpl'] = 'default';
    $this->initListSlicesId();
    $this->initListTagPath();
    // Подписка на новые записи раздела
    $this->d['subscribedNewItems'] = Notify_SubscribePages::subscribed($this->userId, 'items_new', $this->page['id']);
  }

  /**
   * Определяет ID для всех слайсов находящихся в списке записей (перед ними и после)
   */
  protected function initListSlicesId() {
    if (empty($this->page['settings']['listSlicesType'])) {
      $this->d['listSlicesId'] = $this->page['id'];
      return;
    }
    elseif (preg_match('/tag_(\w+)/', $this->page['settings']['listSlicesType'], $m)) {
      if (!$this->tagsSelected) {
        $this->d['listSlicesId'] = $this->page['id'];
      }
      elseif (($tag = Arr::getValueByKey($this->tagsSelected, 'groupName', $m[1])) !== false) {
        $this->d['listSlicesId'] = $this->page['id'].'_'.$tag['id'];
        $this->d['listSlicesTitle'] = $tag['title'];
      }
    }
    elseif (preg_match('/v_(\w+)/', $this->page['settings']['listSlicesType'], $m)) {
      $this->d['listSlicesId'] = Arr::get_value($this->im->items->cond->filters['filter'], 'key', $m[1], 'value');
    }
    if (empty($this->d['listSlicesId'])) throw new Exception("\$this->d['listSlicesId'] is empty");
  }

  ////////////////////////////////
  /////// Параметры для выборок
  ////////////////////////////////

  /**
   * Определяет если параметры включают экшн 'list'
   *
   * @return bool
   */
  protected function isListParams() {
    return (isset($this->req->params[$this->paramFilterN()]) and preg_match('/^t2|t|d|u|v|ne|mx$/', $this->req->params[$this->paramFilterN()]));
  }

  //////////////////// Tags ///////////////////////
  private $tags;

  private $tagsTypes;

  public $tagsSelected = [];

  protected $filterParams = [];


  protected function initUserGroupFilter() {
    if (!$this->userGroup) return;
    $this->im->items->addF('userGroupId', $this->userGroup['id']);
  }

  /**
   * Добавляет данные 'items' и 'pNums' в массив $this->d
   */
  protected function initItems() {
    $this->initFilterByParams();
    $this->initUserGroupFilter();
    $this->im->items->setPagination(true);
    //$this->oManager->items->cond->addF('pageId', $this->page['id']); // на ao id не заполнен
    $this->im->items->cond->setOrder($this->order);
    $hookPaths = Hook::paths('dd/initItems', $this->page['module']);
    if ($this->itemsCacheEnabled) {
      $cacher = new DdItemsCacher($this->im->items, $this->ddo(), ['page'.$this->page['id']]);
      if ($hookPaths) foreach ($hookPaths as $path) include $path;
      $this->d['itemsHtml'] = $cacher->initHtml()->html();
    }
    else {
      if ($hookPaths) foreach ($hookPaths as $path) include $path;
      $this->d['items'] = $this->im->items->getItems();
    }
    $this->d['pagination'] = $this->im->items->getPagination();
  }

  protected function ddo($layout = 'siteItems') {
    return DdoPageSite::factory($this->page, $layout);
  }

  public $itemsCacheEnabled;

  /**
   * Добавляет данные 'items' без разбивки на страницы
   */
  function setItemsOnItem() {
    if (!empty($this->page['settings']['tagField'])) {
      $oTags = DdTags::get($this->strName, $this->page['settings']['tagField']);
      if (!$oTags->group->multi and
        !empty($this->tagsSelected[0])
      ) {
        $this->im->items->addF('id', DdTags::items($this->strName, $this->page['settings']['tagField'])->getIdsByTagId($this->tagsSelected[0]['id']));
      }
    }
    $this->im->items->cond->setOrder($this->order);
    if (!empty($this->page['settings']['setItemsOnItemLimit'])) $this->im->items->cond->setLimit($this->page['settings']['setItemsOnItemLimit']);
    $this->d['items'] = $this->im->items->getItems();
  }

  ///////////// Begin Move Actions /////////////////

  function action_moveForm() {
    $this->d['tpl'] = 'move';
    $this->d['postAction'] = 'moveForm2';
  }

  function action_moveForm2() {
    $this->action_moveForm();
    $this->setMoveStep2Data();
    $this->d['postAction'] = 'move';
  }

  function action_moveGroupForm() {
    $this->d['tpl'] = 'move';
    $this->d['itemIds'] = $this->req->rq('itemIds');
    $this->d['postAction'] = 'moveGroupForm2';
  }

  function action_ajax_deleteGroup() {
    foreach ($this->req->rq('itemIds') as $itemId) $this->im->delete($itemId);
  }

  function action_moveGroupForm2() {
    $this->action_moveGroupForm();
    $this->setMoveStep2Data();
    $this->d['postAction'] = 'moveGroup';
  }

  /*
  protected function setMoveStep2Data() {
    $this->d['toPageId'] = $this->req->rq('pageId');
    $this->d['toPageData'] = O::get('Pages')->getNode($this->d['toPageId']);
    $o = new DdStrConverter($this->page['id'], $this->d['toPageId']);
    $this->d['conformance'] = $o->getTitledConformance();
  }
  */

  function action_changeAuthorGroupForm() {
    $this->d['tpl'] = 'changeAuthor';
    $this->d['itemIds'] = $this->req->rq('itemIds');
    $this->d['postAction'] = 'changeAuthorGroup';
  }

  function action_changeAuthorGroup() {
    db()->query("UPDATE dd_i_{$this->strName} SET userId=?d WHERE id IN (?a)", $this->req->rq('userId'), $this->req->rq('itemIds'));
    $this->redirect();
  }

  ///////////// End Move Actions /////////////////

  function extendItemData() {
  }

  function action_move() {
    $this->hasOutput = false;
    if (empty($this->itemId)) throw new Exception('$this->itemId is empty');
    $this->im->items->move([$this->itemId], $this->req->rq('pageId'));
    $this->moveRedirect($this->req->rq('pageId'));
  }

  function action_moveGroup() {
    $this->hasOutput = false;
    if (!isset($_POST['itemIds']) or !count($_POST['itemIds'])) throw new Exception("\$_POST['itemIds'] is not defined or not an array");
    if (empty($this->req->r['pageId'])) throw new Exception("\$this->req->r['pageId'] is empty");
    $this->im->items->move($_POST['itemIds'], $this->req->r['pageId']);
    $this->moveRedirect($this->req->r['pageId']);
  }

  protected $moveRedirectFunc;

  protected function setMoveRedirect($code) {
    $this->moveRedirectFunc = create_function(null, $code);
  }

  protected function moveRedirect($pageId) {
    $this->completeRedirect();
  }

  protected function initItemPath() {
    if (!$this->itemData) return; // Если не определены данные конкретной записи, то ничего делать и не нужно
    if (!empty($this->page['settings']['tagField'])) {
      $oTags = DdTags::get($this->strName, $this->page['settings']['tagField']);
      $this->setTagTreePath($oTags, $this->itemData[$this->page['settings']['tagField']]);
    }
    if (!empty($this->page['settings']['titleField']) and
      isset($this->itemData[$this->page['settings']['titleField']])
    ) {
      $this->setCurrentPathData($this->itemData[$this->page['settings']['titleField']]);
      $this->setPageTitle($this->itemData[$this->page['settings']['titleField']]);
    }
    else {
      $title = !empty($this->itemData['title']) ? $this->itemData['title'] : '...';
      $this->setPathData($this->tt->getPath(1).'/'.$this->itemData['id'], $title);
      $this->setPageTitle($title);
    }
  }

  protected function initItemTagsData() {
    foreach ($this->d['fields'] as $k => $fld) {
      if (strstr($fld['type'], 'tags')) {
        $flds[] = $k;
      }
    }
    if (!isset($flds)) return;
    foreach ($flds as $fld) $this->d['tags'][$fld] = DdTags::get($this->strName, $fld)->getData();
    // Действия только для случаев, если в настройках раздела выбрано поле 'tagField'
    if (!empty($this->page['settings']['tagField']) and
      !empty($this->itemData[$this->page['settings']['tagField']])
    ) {
      // Выбранные тэги
      $this->tagsSelected[] = isset($this->itemData[$this->page['settings']['tagField']][0]) ? $this->itemData[$this->page['settings']['tagField']][0] : $this->itemData[$this->page['settings']['tagField']];
      $this->d['tagsSelected'] = $this->tagsSelected;
    }
  }

  /**
   * Добавляет ссылку на тэг в хлебные крошки
   */
  protected function initListTagPath() {
    if (empty($this->tagsSelected)) return;
    // Древовидный путь может строиться только по определенному в настройках тэгу
    if (isset($this->tagsSelected[0])) {
      $tags = DdTags::get($this->strName, $this->tagsSelected[0]['groupName']);
      if ($tags->group->tree and $this->setTagTreePath($tags, [$tags->getBranchFromRoot($this->tagsSelected[0]['id'])])) return;
    }
    Misc::checkEmpty($this->tagsSelected[0]['title']);
    $this->setPageTitle($this->tagsSelected[0]['title']);
    $this->setPathData($this->tt->getPath(), $this->tagsSelected[0]['title']);
  }

  protected function setTagTreePath(DdTagsTagsBase $oTags, array $branchFromRoot) {
    // Только для древовидного не мульти-типа при наличии
    if (!$oTags->group->tree or $oTags->group->multi) return false;
    $tagsFlat = DdTagsHtml::treeToList($branchFromRoot);
    foreach ($tagsFlat as $v) {
      $this->setPathData($this->tt->getPath(1).'/t2.'.$oTags->group->name.'.'.$v['id'], $v['title']);
    }
    // Определяем заголовком страницы последний тэг дерева
    $this->setPageTitle($tagsFlat[count($tagsFlat) - 1]['title']);
    return true;
  }

  function action_showItem() {
    $this->d['tpl'] = 'item';
    if (!$this->setItemData()) {
      // Если Записи с таким ID не существует
      $this->error404('Записи с таким ID не существует');
      return;
    }
    if ($this->itemData['pageId'] != $this->page['id']) {
      // Если Запись принадлежит не этому разделу
      $this->error404('Запись принадлежит не этому разделу');
      return;
    }
    if (!$this->itemData['active'] and !$this->allowAction('edit')) {
      // Если Запись не активна и нет прав на её редактирование
      $this->error404('Запись не активна или нет прав на её редактирование');
      return;
    }
    $this->extendItemData();
    $this->initItemTagsData();
    $this->d['item'] = $this->itemData;
    $this->d['ddo'] = $this->ddo('siteItem')->setItem($this->d['item']);
    if (!empty($this->page['settings']['setItemsOnItem'])) $this->setItemsOnItem();
    $this->click(); // Клик
    // Комменты
    if (isset($this->subControllers['comments'])) {
      $this->subControllers['comments']->action_default();
      $this->d['showCommentsAfterItem'] = true;
    }
    $this->itemAuthorMode();
    $this->initListTagPath();
    // Получаем существующие месяца
    /*
    if (!empty($this->page['settings']['dateField'])) {
      if (! isset($this->itemData[$this->page['settings']['dateField']]))
        throw new Exception('No dateField');
      $this->d['year'] = date('Y',
        $this->itemData[$this->page['settings']['dateField'] . '_tStamp']);
      $this->d['month'] = date('n',
        $this->itemData[$this->page['settings']['dateField'] . '_tStamp']);
      $this->d['months'] = $this->oManager->items->getMonths(
        $this->page['settings']['dateField']);
    }
    */
  }

  /**
   * Создаёт в данных шаблона html-блок для редактирования страницы
   */
  protected function initPageContentButtons() {
    $this->d['contentButtons'] = '';
    /*
      <? if (isset($d['allowedActions']) and in_array('edit', $d['allowedActions'])) { ?>
        <? if ($d['page']['controller'] == 'profile') { ?>
          <a href="<?= $this->getPath(1).'/'.$d['item']['id'] ?>?a=edit" class="edit" title="Редактировать"><i></i>Редактировать <?= $d['page']['title'] ?></a>
        <? } elseif ($d['item']) {
          if (in_array('activate', $d['allowedActions'])) {
            if ($d['settings']['premoder']) {
              $this->tpl('editBlocks/premoderBlock', $d['item']);
            } else
              $this->tpl('editBlocks/editBlock', $d['item']);
          } else {
            $this->tpl('editBlocks/editOnlyBlock', $d['item']);
          }
        } elseif (DdCore::isStaticController($d['page']['controller'])) {
          $this->tpl('editBlocks/editOnlyBlock', $d['item']);
        }
      } ?>



        <? if ($d['isRss']) { ?>
          <a href="<?= $this->getPath() ?>?a=rss" class="rss tooltip"
             title="RSS «<?= $d['rssTitle'] ? $d['rssTitle'] : $d['page']['title'] ?>»"><i></i></a>
        <? } ?>
        <? if (Config::getVarVar('dd', 'enableSubscribe', true) and $d['isItemsController'] and !$d['page']['mysite']) { ?>
          <? if ($d['action'] == 'showItem') { ?>
            <? if ($d['subscribedNewComments']) { ?>
            <a href="#" class="subscribed" id="btnSubscribe"><i></i></a>
          <? } else { ?>
            <a href="#" class="unsubscribed" id="btnSubscribe"><i></i></a>
          <? } ?>
            <script type="text/javascript">
              new Ngn.SubscribeLink('btnSubscribe', 'NewComments', {
                titleOn: "Подписаться на новые комментарии записи «<?= $d['item']['title'] ?>»",
                titleOff: "Отписаться от новых комментариев записи «<?= $d['item']['title'] ?>»",
                authorized: <?= Auth::get('id') ? 'true' : 'false' ?>
              });
            </script>
          <? } elseif ($d['action'] == 'list')  { ?>
          <? if ($d['subscribedNewItems']) { ?>
            <a href="#" class="subscribed" id="btnSubscribe"><i></i></a>
          <? } else { ?>
            <a href="#" class="unsubscribed" id="btnSubscribe"><i></i></a>
          <? } ?>
            <script type="text/javascript">
              new Ngn.SubscribeLink('btnSubscribe', 'NewItems', {
                titleOn: "Подписаться на новые записи раздела «<?= $d['page']['title'] ?>»",
                titleOff: "Отписаться от новых записей раздела «<?= $d['page']['title'] ?>»",
                authorized: <?= Auth::get('id') ? 'true' : 'false' ?>
              });
            </script>
          <? } ?>
        <? } ?>





    */
  }

  protected function itemAuthorMode() {
    if ($this->page->getS('ownerMode') != 'author') return;
    $this->d['itemUser'] = DbModelCore::get('users', $this->itemData['userId']);
    $this->d['submenu'] = UserMenu::get($this->d['itemUser'], $this->page['id'], $this->action);
  }

  function action_edit() {
    if (!parent::action_edit()) return false;
    if (!empty($this->itemData['title'])) $this->setCurrentPathData($this->itemData['title']);
    return true;
  }

  function action_activate() {
    $this->im->items->activate($this->req->rq('itemId'));
    $this->redirect('referer');
  }

  function action_deactivate() {
    $this->im->items->deactivate($this->req->rq('itemId'));
    $this->redirect('referer');
  }

  /**
   * Публикация записи модератором
   */
  function action_publish() {
    $this->im->items->activate($this->req->rq('itemId'));
    $this->im->items->updatePublishDate($this->req->rq('itemId'));
    $this->completeRedirect();
    /*
    $item = $this->oManager->items->getItem($this->itemId);
    Notify_SenderRobot::send(
      $item['authorId'],
      'Ваша запись была опубликована',
      'Посмотрите её уже скорей: <a href="'.$this->tt->getPath().'">look</a>'
    );
    $this->completeRedirect();
    */
  }

  function action_ajax_activate() {
    $this->im->items->activate($this->req->rq('itemId'));
  }

  function action_ajax_deactivate() {
    $this->im->items->deactivate($this->req->rq('itemId'));
  }

  function action_up() {
    $this->im->items->shiftUp($this->req->rq('itemId'));
    $this->redirect('referer');
  }

  function action_down() {
    $this->im->items->shiftDown($this->req->rq('itemId'));
    $this->redirect('referer');
  }

  function action_ajax_reorder() {
    $this->im->items->reorderItems($this->req->rq('ids'));
  }

  function action_getFile() {
    $fn = $this->req->rq('fn');
    $strData = DdStructure::getData($this->im->items->strName);
    if ($strData['locked'] and !$this->priv['view']) {
      // Раздел имеет структуру с ограниченным доступом, а вы не имеете прав на её просмотр
      $this->error404('Раздел имеет структуру с ограниченным доступом, а вы не имеете прав на просмотр');
      return;
    }
    $itemId = $this->itemId ? $this->itemId : (int)$this->req->r['itemId'];
    if (!($item = $this->im->items->getItem($itemId))) {
      // Нет записи с таким ID
      $this->error404('Нет записи с таким ID');
      return;
    }
    if (!isset($item[$fn])) {
      throw new Exception("Field '$fn' does not exisst");
    }

    if (isset($item[$fn.'_dl'])) {
      $d = [
        $fn.'_dl' => ++$item[$fn.'_dl']
      ];
      $this->im->items->update($itemId, $d);
    }

    $filename = basename($item[$fn]);
    list($name, $ext) = explode('.', $filename);
    $filename = $name.'-'.$item['id'].'.'.$ext;
    header('Content-type: application/download');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    readfile(WEBROOT_PATH.'/'.$item[$fn]);
    $this->hasOutput = false;
  }

  /////////// Tags /////////

  /**
   * Добавляет к шаблонным данным данные тэгов для текущего раздела
   */
  protected function initTagsTplCommonData() {
    return;
    foreach ($this->oManager->form->fields->getTagFields() as $name => $v) {
      $oTags = DdTags::get($this->strName, $name);
      $this->d['tags'][$name] = $oTags->group->tree ? $oTags->getTree() : $oTags->getTags();
    }
    if ($this->tagsSelected) { // Если определены выбранные тэги
      $selNames = Arr::get($this->tagsSelected, 'name'); // Получаем имена этих тэгов
      foreach ($this->d['tags'] as &$tag) { // Проверяем все на "выбранность"
        foreach ($tag as &$v) {
          if (in_array($v['name'], $selNames)) {
            $v['selected'] = true;
          }
        }
      }
    }
  }

  function action_tags() {
    if (empty($this->tags)) throw new Exception('$this->tags is empty');
    $ids = [];
    foreach ($this->tags as $tag) {
      $ids += DdTags::getItemIds($tag, $this->tagType, $this->page['id']);
    }
    $this->im->items->addF('id', $ids);
    $this->im->items->isPages = true;
    $this->d['items'] = $this->im->items->getItems();
    $this->d['pagination']['pNums'] = $this->im->items->pNums;
    $this->setCurrentPathData($this->d['tag']['title']);
    $this->setPageTitle($this->d['tag']['title']);
  }

  function action_ajax_calendar() {
    $this->ajaxOutput = $this->tt->getTpl('common/calendarInner', $this->d['calendar']);
  }

  function action_rss() {
    $limit = !empty($this->page['settings']['rssN']) ? $this->page['settings']['rssN'] : 20;
    $header['title'] = SITE_TITLE.': '.$this->page['title'];
    $header['description'] = $this->page['title'].' '.$_SERVER['SERVER_NAME'];
    $header['link'] = "http://".$_SERVER['SERVER_NAME'].$this->tt->getPath();
    $this->im->items->setN($this->page['settings']['rssN'] ? $this->page['settings']['rssN'] : 20);
    $this->im->items->cond->setOrder('dateCreate DESC');
    $n = 0;
    foreach ($this->im->items->getItems() as $v) {
      $n++;
      $items[] = [
        'title'       => $v[$this->page['settings']['rssTitleField']] ? str_replace('<', '{', str_replace('>', '}', $v[$this->page['settings']['rssTitleField']])) : '{нет заголовка}',
        'description' => !empty($this->page['settings']['rssDescrField']) ? $v[$this->page['settings']['rssDescrField']] : '',
        'link'        => 'http://'.SITE_DOMAIN.'/'.$this->page['path'].'/'.$v['id'],
        'author'      => $v['authorLogin'],
        'guid'        => isset($v['link']) ? $v['link'] : '',
        'pubDate'     => date('r', $v['dateCreate_tStamp']),
        'category'    => $this->page['title']
      ];
      if ($limit == $n) break;
    }
    $this->rss($header, $items);
  }

  function action_authors() {
    /* @var $oDdItems DdItemsPage */
    $oDdItems = $this->im->items;
    $oDdItems->cond->setOrder('u.dateCreate DESC');
    $this->d['items'] = $oDdItems->getAuthors();
    $this->d['tpl'] = 'authors';
    $this->setPageTitle($this->page['title'].' — авторы');
    $this->setPathData($this->tt->getPath().'?a=authors', 'Авторы');
  }

  protected $deletedIds = [];

  function deletePage() {
    parent::deletePage();
    if (!isset($this->im)) $this->initItemsManager();
    $this->im->deleteAll();
  }

  // ------------------------- mysite -------------------------------

  protected $mysiteOwner;

  /**
   * Инициализация функционала для работы контроллера под сабдоменом
   */
  protected function initMysite() {
    if (!empty($this->page['settings']['mysite']) and empty($this->options['subdomain'])) throw new Exception('Controller must work only with subdomain');
    if (!isset($this->options['subdomain'])) return;
    if (!($this->mysiteOwner = DbModelCore::get('users', $this->options['subdomain'], 'name'))) throw new Exception('User with name "'.$this->options['subdomain'].'" does not exists');

    // Привелегии
    if ($this->mysiteOwner == Auth::get('id')) {
      $this->priv['edit'] = 1;
      $this->priv['create'] = 1;
      $this->priv['sub_create'] = 1;
      $this->priv['sub_edit'] = 1;

      $this->initAllowedActions();
    }

    // Хлебные крошки
    $this->d['pathData'][0] = [
      'title' => $this->mysiteOwner['login'],
      'link'  => $this->tt->getPath(0)
    ];

    // Данные пользователя
    $this->d['user'] = $this->mysiteOwner;
    $this->d['user'] += UsersCore::getImageData($this->mysiteOwner['id']);

    // Фильтр
    $this->im->items->addF('userId', $this->mysiteOwner['id']);

    // Другие разделы пользователя
    $this->d['submenu'] = UserMenu::get($this->mysiteOwner, $this->d['page']['id'], $this->action);
  }

}