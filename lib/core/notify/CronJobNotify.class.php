<?php

class CronJobNotify extends CronJobAbstract {

  public $period = 'every10min';
  
  function __construct() {
    $this->enabled = Config::getVarVar('notify', 'enable', true);
  }
  
  function _run() {
    $n = Notify_Send::send();
    print "Выслано уведомлений: $n";
  }
  
}
