<?php

abstract class PbvSubPagesAbstract extends PbvAbstract {

  public $cssClass = 'pbSubMenu';

  abstract protected function getPageId();

  /**
   * @var PagesTreeTpl
   */
  public $pagesTreeTpl;

  protected function init() {
    if (($pageId = $this->getPageId()) === false) return;
    $pagesTreeTpl = PagesTreeTpl::getObjCached($pageId);
    $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`';
    $pagesTreeTpl->setNodesBeginTpl('`<ul>`');
    $pagesTreeTpl->setNodesEndTpl('`</ul></li>`');
    $pagesTreeTpl->setNodeTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl);
    $pagesTreeTpl->setLeafTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl.'.`</li>`');
    $pagesTreeTpl->setDepthLimit($this->pageBlock['settings']['openDepth']);
    if (($currentPageId = R::get('currentPageId')) !== false) $pagesTreeTpl->setCurrentId($currentPageId);
    $pagesTreeTpl->setBreadcrumbsIds(R::get('breadcrumbsPageIds'));
    $this->pagesTreeTpl = $pagesTreeTpl;
  }

  function _html() {
    if (!isset($this->pagesTreeTpl)) return '';
    return $this->pagesTreeTpl->html();
  }

}
