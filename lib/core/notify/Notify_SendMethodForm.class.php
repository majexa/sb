<?php

class Notify_SendMethodForm extends Form {
  
  function __construct() {
    /* @var $oNotify_Sender Notify_Sender */
    $oNotify_Sender = O::get('Notify_Sender');
    $titles = [
      'privMsgs' => LANG_MESSAGES,
      'email' => LANG_EMAIL
    ];
    foreach ($oNotify_Sender->getSendMethods() as $method)
      $options[$method] = $titles[$method];
      
    parent::__construct(
      new Fields([
        [
          'title' => 'Методы отправки уведомлений',
          'name' => 'sendMethods',
          'type' => 'multiselect',
          'options' => $options,
          'required' => true
        ]
      ])
    );
    $this->setElementsData([
      'sendMethods' => UserSettings::get(Auth::get('id'), 'sendMethods')
    ]);
  }
  
  protected function _update(array $data) {
    UserSettings::set(Auth::get('id'), ['sendMethods'], $data['sendMethods']);
  }
  
}
