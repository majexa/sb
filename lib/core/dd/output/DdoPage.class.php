<?php

class DdoPage extends Ddo {

  protected $page;

  function __construct(DbModelPages $page, $layoutName, array $options = []) {
    $this->page = $page;
    $cls = empty($page['settings']['ddItemsLayout']) ? 'list' : $page['settings']['ddItemsLayout'];
    $this->ddddItemsBegin = '`<div class="items ddItems '.$cls.' str_`.$strName.`">`';
    $this->ddddItemLink = '`/'.$this->page['path'].'/`.$id';
    parent::__construct($this->page['strName'], $layoutName, $options);
  }

  protected function getSettings() {
    return new DdoPageSettings($this->page);
  }

  function itemsBegin() {
    if (($s = parent::itemsBegin()) === '') return '';
    $sflm = Sflm::lib('css');
    return $sflm->getTag($sflm->getCachedUrl('s2/css/common/ddItemWidth?pageId='.$this->page['id'])).$s;
  }

}
