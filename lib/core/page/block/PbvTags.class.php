<?php

class PbvTags extends PbvAbstract {

  protected function init() {
    $this->cssClass = 'pbSubMenu';
    if ($this->pageBlock['settings']['hideSubLevels']) $this->cssClass .= ' hideSubLevels';
  }

  function _html() {
    $page = DbModelCore::get('pages', $this->pageBlock['settings']['pageId']);
    $tags = DdTags::get($page['strName'], $this->pageBlock['settings']['tagField']);
    $selectedIds = [];
    if (!empty($this->ctrl) and !empty($this->ctrl->d['tagsSelected'])) {
      $tagsSelected = DdTagsHtml::treeToList($this->ctrl->d['tagsSelected']);
      if (DdCore::isItemsController($this->ctrl->page['controller'])) {
        $selectedIds = Arr::get($tagsSelected, 'id');
      }
    }
    $html = '<div class="data">'.json_encode(['groupId' => $tags->group->id]).'</div>';
    $param = $tags->group->tree ? 't2.`.$groupName.`.`.$id' : 't.`.$groupName.`.`.$name';
    $nodes = $tags->getData();
    $dddd = '`<a href="/'.$page['path'].'/'.$param.'.`"><i></i><span>`.$title.'.($this->pageBlock['settings']['showTagCounts'] ? '` <span>(`.$cnt.`)</span>' : '`').'</span></a>`';
    if ($this->pageBlock['settings']['showOnlyLeafs'] and 0) {
      $html .= DdTagsHtml::treeOnlyNotEmptyLeafs($nodes, $dddd);
    }
    else {
      $html .= DdTagsHtml::treeUl($nodes, $dddd, $selectedIds, $this->pageBlock['settings']['showNullCountTags']);
    }
    return $html;
  }

}