<?php

class PagesTreeTpl extends DbTreeTpl {
  use Options;

  private $currentId;
  private $breadcrumbsIds = [];

  protected function defineOptions() {
    return [
      'onlyOnMenu' => true,
      'expandItems' => false
    ];
  }

  function __construct(array $options = []) {
    $this->setOptions($options);
    $this->init();
    $onMenuCond = $this->options['onlyOnMenu'] ? ' AND onMenu=1' : '';
    if (($tree = $this->getSubTree(db()->query("
        SELECT
          id,
          title,
          name,
          link,
          path,
          strName,
          settings,
          id       AS ARRAY_KEY, 
          parentId AS PARENT_KEY
        FROM pages
        WHERE active=1 $onMenuCond
        ORDER BY oid
      "), null))
    ) {
      if (Config::getVarVar('menu', 'useTagsAsSubMenu')) {
        $this->setTagsToNodes($tree['childNodes']);
      }
      $this->setNodes($tree);
    }
    else {
      $this->setNodes([]);
    }
  }

  protected function init() {
    $this->setLeafTpl('`<li>`.$title.`</li>`');
    $this->setNodeTpl('`<li>`.$title');
    $this->setNodesBeginTpl('`<ul>`');
    $this->setNodesEndTpl('`</ul></li>`');
  }

  protected function setTagsToNodes(array &$nodes) {
    $showNullCountTags = Config::getVarVar('menu', 'showNullCountTags');
    foreach ($nodes as &$v) {
      $v['settings'] = unserialize($v['settings']);
      if (!empty($v['strName']) and !empty($v['settings']['tagField'])) {
        foreach (DdTags::get($v['strName'], $v['settings']['tagField'])->getData() as $tag) {
          if (!$showNullCountTags and !$tag['cnt']) continue;
          $v['childNodes'][] = [
            'name'  => 'tag_'.$tag['name'],
            'link'  => Tt()->getPath(0).'/'.$v['path'].'/t2.'.$v['settings']['tagField'].'.'.$tag['id'],
            'title' => $tag['title']
          ];
        }
      }
    }
  }

  protected function getSubTree($nodes, $id) {
    return $nodes;
    foreach ($nodes as $_id => $node) {
      if ($_id == $id) return $node;
      if (isset($node['childNodes']) and ($tree = $this->getSubTree($node['childNodes'], $id))) return $tree;
    }
    return false;
  }

  function setCurrentId($id) {
    $this->currentId = $id;
    if (!in_array($id, $this->breadcrumbsIds)) $this->breadcrumbsIds[] = $id;
  }

  function setBreadcrumbsIds($ids) {
    $this->breadcrumbsIds = $ids;
  }

  protected function prepareNode(&$node) {
    if ($node['id'] == $this->currentId) {
      $node['current'] = true;
      $node['class'] = empty($node['class']) ? 'current' : $node['class'].' current';
    }
    if (!empty($this->breadcrumbsIds) and in_array($node['id'], $this->breadcrumbsIds)) {
      $node['active'] = true;
      $node['class'] = empty($node['class']) ? 'active' : $node['class'].' active';
    }
    if (!empty($node['childNodes'])) {
      $node['class'] = empty($node['class']) ? 'hasChildren' : $node['class'].' hasChildren';
    }
    if ($node['link'] == '') $node['link'] = Tt()->getPath(0).$node['path'];
    if ($this->options['expandItems']) {
      $node['items'] = O::get('DdItemsPage', $node['id'])->getItems();
    }
  }

  /**
   * @param $pageId
   * @return false|mixed|PagesTreeTpl
   */
  static function getObjCached($pageId) {
    $cache = FileCache::c();
    if (1 or !($treeTpl = $cache->load('menu_'.$pageId))) {
      $treeTpl = O::get('PagesTreeTpl', $pageId);
      $cache->save($treeTpl, 'menu_'.$pageId, ['pages']);
    }
    return $treeTpl;
  }

}

