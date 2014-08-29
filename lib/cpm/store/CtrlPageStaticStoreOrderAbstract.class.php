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

  protected function init() {
    Sflm::frontend('css')->addPath('cpm/store/products.css');
    Sflm::frontend('js')->addPath('cpm/store/site.js');
  }

  // --

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
    if ($this->processForm(new StoreOrderForm($this->cartItems))) {
      StoreCart::get()->clear();
      $this->redirect($this->tt->getPath(1).'/complete');
    }
  }

  function action_complete() {
    $this->d['tpl'] = 'pageModules/storeOrder/complete';
  }
  
}