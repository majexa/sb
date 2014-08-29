<?php

class PbvItems extends PbvAbstract {

  /**
   * @var DbModelPages
   */
  protected $page;

  /**
   * @var DdItemsPage
   */
  public $items;

  protected function init() {
    $this->items = O::get('DdItemsPage', $this->pageBlock['settings']['pageId']);
    $this->page = DbModelCore::get('pages', $this->pageBlock['settings']['pageId']);
    $this->initItems();
  }

  protected function initItems() {
    $this->items->cond->setLimit(!empty($this->pageBlock['settings']['limit']) ? $this->pageBlock['settings']['limit'] : 5);
    $this->items->cond->setOrder(!empty($this->pageBlock['settings']['order']) ? $this->pageBlock['settings']['order'] : 'dateCreate DESC');
  }

  protected function initButtons() {
    if (strstr($this->pageBlock['settings']['order'], 'DESC')) {
      $orderPath = 'o.'.str_replace(' DESC', '', $this->pageBlock['settings']['order']);
    }
    else {
      $orderPath = 'oa.'.$this->pageBlock['settings']['order'];
    }
    $this->moreLink = [
      'title' => $this->pageBlock['settings']['listBtnTitle'] ? $this->pageBlock['settings']['listBtnTitle'] : 'все',
      'link'  => $this->pageBlock['settings']['listBtnOrder'] == 'block' ? $this->page['path'].'/'.$orderPath : $this->page['path']
    ];
    if (!empty($this->page['settings']['rssTitleField'])) {
      $this->buttons[] = [
        'title' => 'RSS «'.$this->page['title'].'»',
        'class' => 'rss',
        'link'  => $this->page['path'].'?a=rss'
      ];
    }
  }

  function _html() {
    $this->items = $this->items->getItems();
    $ddo = new DdoPageModule($this->page, 'pageBlock');
    $ddo->ddddByName['more'] = '`<a href="`.Tt()->getPath(0).$pagePath.`/`.$id.`"><span>`.$title.`</span></a>`';
    $ddo->setItems($this->items);
    $d['ddo'] = $ddo;
    return Tt()->getTpl('pageBlocks/items', $d);
  }

}