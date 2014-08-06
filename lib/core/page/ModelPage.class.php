<?php

class ModelPage extends ArrayAccesseble {

  function __construct(DbModel $page) {
    $this->r = $page;
  }

  function isDynamic() {
    return $this->r instanceof DbModelPages;
  }

}