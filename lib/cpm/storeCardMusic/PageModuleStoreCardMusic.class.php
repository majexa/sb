<?php

class PageModuleStoreCardMusic extends PageModuleStoreCard {

  public $title = 'Магазин (card/music)';

  protected function ddFields() {
    return array_merge(parent::ddFields(), [
      [
        'title' => 'Трек',
        'name'  => 'track',
        'type'  => 'sound',
        'required' => true
      ]
    ]);
  }

}
