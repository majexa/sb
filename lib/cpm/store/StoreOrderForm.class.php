<?php

class StoreOrderForm extends Form {

  function __construct(array $cartItems, array $options = []) {
    $this->cartItems = $cartItems;
    $this->im = new DdItemsManager(new DdItems('orders'), new DdForm(new DdFields('orders'), 'orders'));
    parent::__construct(new Fields(), array_merge(['submitTitle' => 'Отправить заказ'], $options));
  }

  protected function defineOptions() {
    return array_merge(parent::defineOptions(), [
      'cartProductsTitle'  => 'Вы покупаете следующите товары',
      'jsOrderListOptions' => []
    ]);
  }

  protected $data = [];

  protected function init() {
    if (Auth::get('id')) {
      $this->data['fullName'] = Auth::get('login');
      if (Auth::get('phone')) $this->data['phone'] = Auth::get('phone');
      $this->fields->fields = array_merge($this->im->form->fields->getFieldsF(), $this->getAuthorizedFields());
    }
    else {
      $this->fields->fields = $this->im->form->fields->getFieldsF();
    }
    $this->fields->fields = array_merge([$this->getCartProductsField()], $this->fields->getFieldsF());
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
    $orderId = $this->im->create(array_merge($data, $this->data));
    if (($behaviors = Config::getVarVar('store', 'orderBehaviors', true))) {
      foreach ($behaviors as $v) Ngn::fireEvent("store.newOrder.$v", [
        $orderId,
        $this->cartItems,
      ]);
    }
  }

}
