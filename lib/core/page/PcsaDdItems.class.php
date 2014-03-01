<?php

class PcsaDdItems extends Pcsa {

  function action(array $initSettings) {
    if (!empty($initSettings['manualOrder'])) {
      if (!O::get('DdFields', $initSettings['strName'])->exists('oid')) {
        O::get('DdFieldsManager', $initSettings['strName'])->create([
          'title' => LANG_ORDER_NUM,
          'name' => 'oid',
          'type' => 'num',
          'system' => true,
          'oid' => 300
        ]);
      }
      $initSettings['order'] = 'oid';
    }
    return $initSettings;
  }

}