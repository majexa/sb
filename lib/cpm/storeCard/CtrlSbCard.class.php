<?php

class CtrlSbCard extends CtrlDefault {

  function action_json_default() {
    if (($r = $this->jsonFormActionUpdate(new CardPaymentForm('0.1'))) === true) {
      $this->json['asd'] = 123;
    }
    return $r;
  }

}