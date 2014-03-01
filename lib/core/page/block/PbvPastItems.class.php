<?php

class PbvPastItems extends PbvItems {
  
  protected function initItems() {
    $this->items->cond->setLimit(!empty($this->pageBlock['settings']['limit']) ?
      $this->pageBlock['settings']['limit'] : 5);
    $this->items->cond->setOrder(!empty($this->pageBlock['settings']['order']) ?
      $this->pageBlock['settings']['order'] :
      'dateCreate DESC');
    $this->items->cond->addRangeFilter(
      $this->pageBlock['settings']['dateField'],
      date('Y-m-d', time()-60*60*24*$this->pageBlock['settings']['period']),
      date('Y-m-d')
    );
  }

}
