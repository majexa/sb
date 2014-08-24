<?php

//throw new Exception(' need to refactor "breadcrumbsPageIds"');

abstract class PbvSubPagesAbstract extends PbvAbstract {

  public $cssClass = 'pbSubMenu';

  abstract protected function getPageId();

  /**
   * @var PagesTreeTpl
   */
  protected $pagesTreeTpl;

  protected function init() {
    Sflm::frontend('css')->addPath('i/css/common/tree.css');
    if (($pageId = $this->getPageId()) === false) return;
    $this->pagesTreeTpl = new PagesMenuUl;
  }

  function _html() {
    if (!isset($this->pagesTreeTpl)) return '';
    return $this->pagesTreeTpl->html();
  }

}
