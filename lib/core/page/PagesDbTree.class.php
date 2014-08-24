<?php

class PagesDbTree extends DbTree {

  function __construct() {
    parent::__construct('pages');
  }

  function getRoot() {
    return [
      'title'    => 'root',
      'id'       => -1,
      'parentId' => 0,
      'active'   => 1,
      'onMenu'   => 1,
      'folder'   => 1
    ];
  }

}