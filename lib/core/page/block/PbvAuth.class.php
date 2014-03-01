<?php

class PbvAuth extends PbvAbstract {

  function _html() {
    return Tt()->getTpl('auth/login');
  }

}
