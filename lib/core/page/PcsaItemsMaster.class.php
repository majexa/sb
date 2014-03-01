<?php

class PcsaItemsMaster extends Pcsa {
  
  function action(array $initSettings) {
    if (empty($initSettings['slavePageId'])) return $initSettings;
    DbModelPages::addSettings(
      $initSettings['slavePageId'],
      Arr::filterByKeys($initSettings, [
        'mysite', 'ownerMode'
      ])
    );
    return $initSettings;
  }

}