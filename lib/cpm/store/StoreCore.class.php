<?php

class StoreCore {
  
  static function getDeliveryWays() {
    return [
      'post' => 'Почта',
      'exspressDelivery' => 'Экспресс-доставка на дом',
      'transportCompany' => 'Транспортная компания',
      'self' => 'Самовывоз / Личная встреча',
      'trainConductor' => 'Проводник поезда',
      'courier' => 'Курьер'
    ];
  }
  
  static function getPaymentWays() {
    return [
      'cache' => 'Наличные',
      'bankTransfer' => 'Банковский перевод на счёт',
      'postTransfer' => 'Почтовый перевод',
      'cardTransfer' => 'Перевод на банковскую карту',
      'epay' => 'Электронные платёжные системы',
      'transferSystem' => 'Система денежных переводов',
      'mobile' => 'Пополнение счёта мобильного телефона'
    ];
  }
  
  static function getOrderController() {
    return 'storeOrder'.ucfirst(Config::getVarVar('store', 'orderControllerSuffix'));
  }

}