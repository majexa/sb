<?php

require NGN_ENV_PATH.'/authorizeNet/autoload.php';

class CardPaymentForm extends Form {

  protected $amount;

  function __construct($amount, array $fields = []) {
    $this->amount = $amount;
    Config::loadConstants('authorizeNet');
    Misc::checkConst('AUTHORIZENET_API_LOGIN_ID');
    Misc::checkConst('AUTHORIZENET_TRANSACTION_KEY');
    parent::__construct(array_merge($fields, [
      [
        'title' => 'Номер банковской карты',
        'name'  => 'card_num',
        'type'  => 'num'
      ],
      [
        'title' => 'Expiration date (mm/yy)',
        'name'  => 'exp_date',
      ]
    ]), [
      'submitTitle' => 'Оплатить'
    ]);
  }

  protected function _update(array $data) {
    $sale = new AuthorizeNetAIM;
    $sale->amount = '0.1';
    $sale->card_num = $data['card_num'];
    $sale->exp_date = $data['exp_date'];
    // VALIDATION (!)
    // $response = $sale->authorizeAndCapture();
  }

}