<?php

abstract class CtrlPageStaticStoreOrderAbstract extends CtrlPageStatic {

  static function page() {
    return [
      'title' => 'Заказ',
      'module' => 'store'
    ];
  }

  protected function getLayoutN() {
    return 11;
  }

  protected $cartItems;
  
  protected function initCartItems() {
    $this->cartItems = StoreCart::get()->getItems();
  }

  function action_default() {
    $this->initCartItems();
    if (!$this->cartItems) {
      $this->d['tpl'] = 'pageModules/storeOrder/empty';
      return;
    }
    if ($this->processForm($this->storeOrderForm())) {
      StoreCart::get()->clear();
      $this->redirect($this->tt->getPath(1).'/complete');
    }
  }

  protected function storeOrderForm() {
    return new StoreOrderForm($this->cartItems);
  }

  function action_complete() {
    $this->d['tpl'] = 'pageModules/storeOrder/complete';
  }
  
}