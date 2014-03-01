<?php

class CronJobLevelItemsCollector extends CronJobLevel {
  
  function _run() {
    $o = new LevelItemsCollector();
    print 'Собрано '.$o->collect().' новых записей для назначения уровней<br />';
  }
  
} 