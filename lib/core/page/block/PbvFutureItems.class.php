<?php

class PbvFutureItems extends PbvItems {

  protected function initItems() {
    $items = O::get('DdItemsPage', $this->pageBlock['settings']['pageId']);
    $items->cond->setLimit(!empty($this->pageBlock['settings']['limit']) ? $this->pageBlock['settings']['limit'] : 5);
    $items->cond->setOrder(!empty($this->pageBlock['settings']['order']) ? $this->pageBlock['settings']['order'] : 'dateCreate DESC');
    $items->cond->addRangeFilter($this->pageBlock['settings']['dateField'], date('Y-m-d'), date('9000-01-01'));
    $this->items = $items->getItems();
  }

}
