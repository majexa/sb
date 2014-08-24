<?php

class PagesMenuUl extends PagesMenu {

  protected function init() {
    $this->setNodesBeginTpl('`<ul>`');
    $this->setNodesEndTpl('`</ul></li>`');
    $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`';
    $this->setNodeTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl);
    $this->setLeafTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl.'.`</li>`');
    $this->setDepthLimit(2);
    //if (($currentPageId = R::get('currentPageId')) !== false) $treeTpl->setCurrentId($currentPageId);
    //$treeTpl->setBreadcrumbsIds(R::get('breadcrumbsPageIds'));
  }

}