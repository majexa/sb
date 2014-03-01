<?php

class Notify_SubscribeText_items {
  
  function getData_new($userId, $lastSendTime) {
    $data = [];
    foreach (Notify_SubscribePages::getSubscribedItems($userId, 'items_new') as $v) {
      $page = DbModelCore::get('pages', $v['pageId']);
      $oDdItems = O::get('DdItemsPage', $page['id']);
      $oDdItems->setOrder('datePublish DESC');
      $oDdItems->addRangeFilter(
        'datePublish', $lastSendTime, date('Y-m-d H:i:s', time()+99999));
      if (!($items = $oDdItems->getItems())) continue;
      $data[$v['pageId']]['items'] = $items;
      $data[$v['pageId']]['data'] = $page;
    }
    return $data;
  }
  
  function getTpl_new($data) {
    $html = '';
    foreach ($data as $page) {      
      /* @var $ddoFields DdoFields */
      $ddoFields = O::get('DdoFields', $page['data']['strName'], 'eventsInfo');
      $ddoFields->getSystem = false;
      $fields = $ddoFields->getFields();
      
      /* @var $ddo DdoPage */
      $ddo = O::get(
        'DdoPage', $page['data']['strName'], $page['data']['path'])->
          setItems($page['items']);
      $ddo->setFields($fields);
      $html .= Tt()->getTpl('notify/msgs/items_new', [
        'ddo' => $ddo,
        'page' => $page
      ]);
    }
    return $html;
  }
  
}