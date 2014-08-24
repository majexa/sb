<?php

throw new Exception(' need to refactor "currentPageId", "breadcrumbsPageIds"');


class MenuOld {

  /**
   * Возвращает HTML дерева разделов в виде маркерованого списка.
   * Пример:
   * <ul>
   *   <li><a href="/o-nas">О нас</a></li>
   *   <li>
   *     <a href="/reshenija">Решения</a>
   *     <ul>
   *       <li><a href="/reshenija.galerei">Галереи</a></li>
   *     </ul>
   *   <li><a href="/platforma">Платформа</a></li>
   *   <li><a href="/servisi">Сервисы</a></li>
   *   </li>
   * </ul>
   *
   * @param string $name Имя раздела
   * @param int $depthLimit Глубина
   * @param string $linkTpl Шаблон тэга ссылки
   * @return string
   */
  static function ul($name, $depthLimit = 1, $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`') {
    return self::getUlObj($name, $depthLimit, $linkTpl)->html();
  }

  static function getUlObj($name, $depthLimit = 1, $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`') {
    if (empty($linkTpl)) throw new Exception('$linkTpl can not be empty');
    return self::getUlObjById(DbModelCore::get('pages', $name, 'name')->r['id'], $depthLimit, $linkTpl);
  }

  static function getUlObjById($pageId, $depthLimit = 1, $linkTpl = '`<a href="`.$link.`"><span>`.$title.`</span></a>`') {
    $treeTpl = PagesTreeTpl::getObjCached($pageId);
    $treeTpl->setNodesBeginTpl('`<ul>`');
    $treeTpl->setNodesEndTpl('`</ul></li>`');
    $treeTpl->setNodeTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl);
    $treeTpl->setLeafTpl('`<li id="mi_`.Misc::name2id($name).`"`.(!empty($class) ? ` class="`.$class.`"` : ``).`>`.'.$linkTpl.'.`</li>`');
    $treeTpl->setDepthLimit($depthLimit);
    if (($currentPageId = R::get('currentPageId')) !== false) $treeTpl->setCurrentId($currentPageId);
    $treeTpl->setBreadcrumbsIds(R::get('breadcrumbsPageIds'));
    return $treeTpl;
  }

  static function flatByPageId($pageId, $linkTpl, $sep = '') {
    $treeTpl = PagesTreeTpl::getObjCached($pageId);
    $treeTpl->setDepthLimit(1);
    $treeTpl->setNodesBeginTpl('');
    $treeTpl->setNodesEndTpl('');
    $treeTpl->setTpl($linkTpl);
    $treeTpl->setSeparator($sep);
    $treeTpl->setCurrentId(R::get('currentPageId'));
    $treeTpl->setBreadcrumbsIds(R::get('breadcrumbsPageIds'));
    return $treeTpl->html();
  }

  static function flat($name, $linkTpl, $sep = '') {
    return self::flatByPageId(DbModelCore::get('pages', $name, 'name')->r['id'], $linkTpl, $sep);
  }

  static function flatLevel2($curPageId, $linkTpl, $sep = '') {
    $parents = (new PagesDbTree)->getParentsReverse($curPageId);
    return self::flatByPageId($parents[1]['id'], $linkTpl, $sep);
  }

  static function simple($name) {
    return self::flat($name, '`<a href="`.$link.`"><span>`.$title.`<span></a>`', ' | ');
  }

}
