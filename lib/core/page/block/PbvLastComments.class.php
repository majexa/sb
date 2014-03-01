<?php

class PbvLastComments extends PbvAbstract {
  
  /**
   * @var CommentsCollection
   */
  public $comments;
  
  protected function init() {
    $this->comments = Comments::getLast(!empty($this->pageBlock['settings']['limit']) ?
      $this->pageBlock['settings']['limit'] : 5);
  }
  
  function _html() {
    return
      ($this->pageBlock['settings']['title'] ? '<h2>'.$this->pageBlock['settings']['title'].'</h2>' : '').
      Tt()->getTpl(
        'common/lastComments',
        $this->comments->getItems()
      );
  }
  
}