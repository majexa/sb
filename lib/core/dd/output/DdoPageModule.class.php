<?php

class DdoPageModule extends DdoPage {

  /*
  protected function initTpls() {
    parent::initTpls();
    $this->ddddByName['title'] = '`<h2><a href="`.'.$this->ddddItemLink.'.`">`.$v.`</a></h2>`';
    $this->ddddByType['date'] = 'dateStrSql($v, `d.m.Y`, `Y-m-d`)';
    $this->ddddByType['datetime'] = 'datetimeStrSql($v)';
    $this->ddddByType['commentsCount'] = '
$v ? (`<div class="smIcons">
<a class="comments`.($v > 2 ? `2` : ``).` shortComments"
title="комментарии (`.$v.`)" 
href="`.$pagePath.`/`.$id.`#msgs"><i></i>
`.$v.`
</a><div class="clear"><!-- --></div>
</div>`) : ``';

    $this->ddddByType['clicks'] = '$v ? `<div class="smIcons"><span class="view" title="Просмотров"><i></i>`.$v.`</span></div>` : ``';
  }
  */

  /**
   * @return DdoPageFields
   */
  protected function ddoFields() {
    $class = 'DdoPageFields'.ucfirst($this->page['module']);
    if (!class_exists($class)) $class = 'DdoPageFields';
    return new $class($this->settings, $this->layoutName, $this->strName, empty($this->options['fieldOptions']) ? [] : $this->options['fieldOptions']);
  }

  /**
   * @param DbModelPages $page
   * @param $layoutName
   * @param array $options
   * @return DdoPageModule
   */
  static function factory(DbModelPages $page, $layoutName, array $options = []) {
    //$class = empty($page['module']) ? 'DdoPageModule' : 'DdoPageModule'.ucfirst($page['module']);
    $class = 'DdoPageModule'.ucfirst($page['module']);
    if (!class_exists($class)) $class = 'DdoPageModule';
    return new $class($page, $layoutName, $options);
  }

}
