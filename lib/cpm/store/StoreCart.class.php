<?php

/**
 * Объект маппится в контроллере
 */
class StoreCart {

  static function get() {
    return new StoreCart();
  }

  protected $sessionId;

  public $required = ['cartId'];

  function __construct($sessionId = null) {
    $this->sessionId = $sessionId ? $sessionId : session_id();
    Misc::checkEmpty($this->sessionId);
  }

  protected function validate($cartId) {
    $r = [];
    foreach (explode('-', $cartId) as $v) {
      $v = (int)$v;
      $r[] = $v;
    }
    return implode('-', $r);
  }

  function add($pageId, $cartId, $cnt) {
    $cartId = $this->validate($cartId);
    db()->create('storeCart', [
      'sessionId'  => $this->sessionId,
      'pageId'     => $pageId,
      'cartId'     => $cartId,
      'cnt'        => $cnt,
      'dateUpdate' => dbCurTime()
    ], true);
  }

  function delete($pageId, $cartId) {
    db()->query('DELETE FROM storeCart WHERE sessionId=? AND pageId=? AND cartId=?', $this->sessionId, $pageId, $cartId);
  }

  function getIds() {
    $cartItems = db()->query('SELECT pageId, cartId, cnt FROM storeCart WHERE sessionId=?', $this->sessionId);
    $orderParams = Config::getVarVar('store', 'orderParams', true);
    foreach ($cartItems as &$v) {
      $params = explode('-', $v['cartId']);
      $v['itemId'] = $params[0];
      if ($orderParams and count($params) > 1) {
        $orderParamValues = array_slice($params, 1, count($params));
        if (empty($orderParamValues)) continue;
        $strName = DbModelCore::get('pages', $v['pageId'])->r['strName'];
        $fields = (new DdFields($strName))->fields;
        foreach ($orderParams as $i => $name) {
          if (empty($orderParamValues[$i])) continue;
          $v['orderParams'][$name]['title'] = $fields[$name]['title'];
          $v['orderParams'][$name]['value'] = DdTags::get($strName, $name)->getItem($orderParamValues[$i]);
        }
      }
    }
    return $cartItems;
  }

  function getItems() {
    $items = [];
    foreach (DdCore::extendItemsData($this->getIds()) as $v) $items[] = $v;
    return empty($items) ? false : $items;
  }

  function updateCnt($pageId, $cartId, $cnt) {
    db()->query('UPDATE storeCart SET cnt=?d WHERE pageId=?d AND cartId=?d', $cnt, $pageId, $cartId);
  }

  function clear() {
    db()->query('DELETE FROM storeCart WHERE sessionId=?', $this->sessionId);
    return $this;
  }

}