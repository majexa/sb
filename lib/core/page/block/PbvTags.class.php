<?php

class PbvTags extends PbvAbstract {

  /**
   * @var DdTagsTagsBase
   */
  public $tags;

  public $page;

  protected function init() {
    $this->cssClass = 'pbSubMenu';
    if ($this->pageBlock['settings']['hideSubLevels']) $this->cssClass .= ' hideSubLevels';
    $this->page = DbModelCore::get('pages', $this->pageBlock['settings']['pageId']);
    $this->tags = DdTags::get($this->page['strName'], $this->pageBlock['settings']['tagField']);
  }

  function _html() {
    $selectedIds = [];
    if (!empty($this->ctrl) and !empty($this->ctrl->d['tagsSelected'])) {
      $tagsSelected = DdTagsHtml::treeToList($this->ctrl->d['tagsSelected']);
      if (DdCore::isItemsController($this->ctrl->page['controller'])) {
        $selectedIds = Arr::get($tagsSelected, 'id');
      }
    }
    $html = '<div class="data">'.json_encode(['groupId' => $this->tags->group->id]).'</div>';
    $param = $this->tags->group->tree ? 't2.`.$groupName.`.`.$id' : 't.`.$groupName.`.`.$name';
    $nodes = $this->tags->getData();
    $dddd = '`<a href="'.$this->page['path'].'/'.$param.'.`"><i></i><span>`.$title.'.($this->pageBlock['settings']['showTagCounts'] ? '` <span>(`.$cnt.`)</span>' : '`').'</span></a>`';
    if ($this->pageBlock['settings']['showOnlyLeafs'] and 0) {
      $html .= DdTagsHtml::treeOnlyNotEmptyLeafs($nodes, $dddd);
    }
    else {
      $html .= DdTagsHtml::treeUl($nodes, $dddd, $selectedIds, $this->pageBlock['settings']['showNullCountTags']);
    }
    return $html;
  }

}