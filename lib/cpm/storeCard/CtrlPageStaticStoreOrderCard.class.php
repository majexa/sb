<?php

class CtrlPageStaticStoreOrderCard extends CtrlPageStaticStoreOrderAbstract {

  static function page() {
    return [
      'title' => 'Заказ (card)',
      'module' => 'storeCard'
    ];
  }

  protected function storeOrderForm() {
    return new StoreOrderFormCard($this->cartItems);
  }


}