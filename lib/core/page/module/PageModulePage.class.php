<?php

abstract class PageModulePage {

  protected $page;

  /**
   * @param DbModelPages $page
   */
  function __construct($page) {
    $this->page = $page;
  }

  function delete() {
    DbModelCore::delete('pages', $this->page['id']);
  }

  /**
   * @param integer|DbModelPages $page
   * @return PageModulePage
   */
  static function get($page) {
    if (is_numeric($page)) $page = DbModelCore::get('pages', $page);
    if (($class = ClassCore::getFirstAncestor($page['module'], 'PageModule', 'PageModulePage')) === false) {
      return false;
    }
    return new $class($page);
  }

}