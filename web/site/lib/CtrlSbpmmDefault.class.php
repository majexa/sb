<?php

class CtrlSbpmmDefault extends CtrlDefault {

  protected function getParamActionN() {
    return 0;
  }

  protected function twoLevelFolderToArray($folder) {
    $r = [];
    foreach (glob("$folder/*") as $n => $subFolder) {
      $r[$n]['title'] = Misc::hyphenate(basename($subFolder), ' ');
      $r[$n]['items'] = array_map(function($path) {
        return str_replace(WEBROOT_PATH, '', $path);
      }, glob("$subFolder/*"));
      asort($r[$n]['items']);
    }
    return $r;
  }

  function action_default() {
    $this->d['captures'] = $this->twoLevelFolderToArray(WEBROOT_PATH.'/captures/pageModules');
  }

}