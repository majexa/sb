<?php

class CtrlPageStaticStoreCardMusic extends CtrlPageStatic {

  static function page() {
    return [
      'title' => 'Ваш товар',
      'module' => 'storeCardMusic'
    ];
  }

  function action_json_buy() {
    if (($r = $this->jsonFormActionUpdate(new CardMusicPaymentForm($this->req->param(2)))) === true) {
      return true;
    }
    return $r;
  }

  function action_default() {
    Arr::checkEmpty($_SESSION, 'bought');
    $this->d['purchasedLink'] = '/u/dd/shop/'.$_SESSION['bought'][0].'/track.mp3';
    $this->d['tpl'] = 'pageModules/storeOrderMusic/default';
  }

}