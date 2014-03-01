<?php

class CronJobPublishItems extends CronJobAbstract {
  
  public $period = 'hourly';
  
  function _run() {
    foreach (DdCore::tables() as $table) {
      db()->query(
        "UPDATE $table SET active = 1 WHERE datePublish < ? AND active = 0",
        date('Y-m_d H:i:s'));
    }
  }
  
}