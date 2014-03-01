<?php

class StoreOrderFormBase extends Form {

  function __construct(array $cartItems, array $options = []) {
    $this->cartItems = $cartItems;
    $this->im = DdCore::getItemsManager(Config::getVarVar('store', 'ordersPageId'));
    $this->im->items->forceDublicateInsertCheck = true;
    parent::__construct(new Fields(), array_merge(['submitTitle' => 'Отправить заказ'], $options));
  }

  protected function defineOptions() {
    return [
      'cartProductsTitle'  => 'Вы покупаете следующите товары',
      'jsOrderListOptions' => []
    ];
  }

  protected $data = [];

  protected function init() {
    if (Auth::get('id')) {
      $this->data['fullName'] = Auth::get('login');
      if (Auth::get('phone')) $this->data['phone'] = Auth::get('phone');
      $this->fields->fields = array_merge($this->im->form->fields->fields, $this->getAuthorizedFields());
    }
    else {
      $this->fields->fields = $this->im->form->fields->fields;
    }
    $this->fields->fields = array_merge([$this->getCartProductsField()], $this->fields->fields);
  }

  protected function getAuthorizedFields() {
    $r = [
      'fullName' => [
        'name'  => 'fullName',
        'type'  => 'staticTitledText',
        'title' => 'Ваше имя',
        'text'  => Auth::get('login')
      ]
    ];
    if (($phone = Auth::get('phone'))) {
      $r['phone'] = [
        'name'  => 'phone',
        'type'  => 'staticTitledText',
        'title' => 'Телефон',
        'text'  => $phone
      ];
    }
    return $r;
  }

  protected function getCartProductsField() {
    return [
      'name'    => 'orderItems',
      'noValue' => true,
      'text'    => '<p><b>'.$this->options['cartProductsTitle'].':</b></p>'.Tt()->getTpl('pageModules/storeOrder/cartProducts', $this->cartItems),
      'type'    => 'staticText',
    ];
  }

  protected function _update(array $data) {
    $id = $this->im->create(array_merge($data, $this->data));
    Ngn::fireEvent('store.newOrder', [$id, $this->cartItems]);
  }

}
