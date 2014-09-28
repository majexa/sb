<?php

class CardMusicPaymentForm extends CardPaymentForm {

  protected $item;

  function __construct($itemId) {
    $this->item = (new DdItems('shop'))->getItem($itemId);
    parent::__construct('0.1', [
      [
        'type' => 'staticText',
        'text' => 'Вы покупаете трек "'.$this->item['title'].'"'
      ]
    ]);
  }

  protected function _update(array $data) {
    parent::_update($data);
    $_SESSION['bought'] = [
      $this->item['id']
    ];
  }

}