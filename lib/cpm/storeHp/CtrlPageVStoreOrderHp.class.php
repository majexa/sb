<?php

class CtrlPageVStoreOrderHp extends CtrlPageVStoreOrder {

  const RETAIL = 0;
  const WHOLESALE = 1;

  static $title = 'Заказ (скрытая цена)';

  protected function initCartItems() {
    parent::initCartItems();
    if ($this->cartItems)
      if (Auth::get('id'))
        foreach ($this->cartItems as &$v) $v['price'] = $v['price2'];
    $this->replacePriceByFirstOrderParamPrice();
  }

  protected function getPrice($v, $type = self::RETAIL) {
    foreach ($v['orderParams'] as $name => $p) {
      if (!Arr::isEmpty($v[$name.'_prices'])) {
        return $v[$name.'_prices'][$type][$p['value']['id']];
      }
    }
  }

  protected function replacePriceByFirstOrderParamPrice() {
    if (!$this->cartItems) return;
    foreach ($this->cartItems as &$v) {
      if (empty($v['orderParams'])) continue;
      $v['price'] = $this->getPrice($v, Auth::get('id') ? self::WHOLESALE : self::RETAIL);
    }
  }
  
}
