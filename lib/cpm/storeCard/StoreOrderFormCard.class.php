<?php

class StoreOrderFormCard extends StoreOrderForm {

  protected function defineOptions() {
    return array_merge(parent::defineOptions(), [
      'submitTitle' => 'Перейти к оплате'
    ]);
  }

  protected function _update(array $data) {
    die2([$this->cartItems, array_merge($data, $this->data)]);
  }

}