<?php

/**
 * Класс для рассылки уведомлений от лица робота (системного пользователя)
 *
 */
class Notify_SenderRobot {
  
  /**
   * @var Notify_Sender
   */
  protected $sender;
  
  protected $defaultSendMethods = ['privMsgs', 'email'];
  
  function __construct() {
    $this->sender = new Notify_Sender();
  }
  
  function send($toUserId, $title, $text) {
    if (NOTIFY_ROBOT_ID == $toUserId)
      throw new Exception('Robot ID and recepient ID can not be equal');
    return $this->sender->send(
      NOTIFY_ROBOT_ID, $toUserId, $this->getSendMethods($toUserId), $title, $text);
  }
  
  function sendIfSubscribed($subscribeType, $toUserId, $title, $text) {
    if (NOTIFY_ROBOT_ID == $toUserId)
      throw new Exception('Robot ID and recepient ID can not be equal');
    return $this->sender->sendIfSubscribed(
      $subscribeType, NOTIFY_ROBOT_ID, $toUserId, $this->getSendMethods($toUserId), $title, $text);
  }
  
  protected function getSendMethods($userId) {
    if (!($sendMethods = UserSettings::get($userId, 'sendMethods')))
      return $this->defaultSendMethods;
    return $sendMethods;
  }
  
}