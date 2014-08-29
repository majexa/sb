<?php

class DdItemsManagerPage extends DdItemsManager {

  function __construct(DbModel $page, DdItems $items, DdForm $form, array $options = []) {
    parent::__construct($items, $form, $options);
    $imageSizes = Arr::filterEmptiesR(Arr::filterByKeys($page['settings'], array_keys($this->imageSizes)));
    $this->imageSizes = array_merge($this->imageSizes, $imageSizes);
    if (!empty($page['settings']['smResizeType'])) $this->smResizeType = $page['settings']['smResizeType'];
  }

}
