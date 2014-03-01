<?php

class CronJobLevelNominate extends CronJobLevel {
  
  function _run() {
    $o = new LevelNominateManager();
    $o->nominate();    
  }
  
}