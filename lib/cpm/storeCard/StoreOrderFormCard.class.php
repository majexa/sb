<?php

class StoreOrderFormCard extends StoreOrderForm {

  protected function defineOptions() {
    return array_merge(parent::defineOptions(), [
      'submitTitle' => 'Перейти к оплате'
    ]);
  }

}