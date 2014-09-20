<?php

class CtrlSbpmmDefault extends CtrlDefault {

  protected function getParamActionN() {
    return 0;
  }

  protected function twoLevelFolderToArray($folder) {
    $r = [];
    foreach (glob("$folder/*") as $k => $subFolder) {
      $r[$k]['title'] = Misc::hyphenate(basename($subFolder), ' ');
      $r[$k]['items'] = array_map(function($path) use ($subFolder) {
        $titles = require "$subFolder/titles.php";
        $n = File::stripExt(basename($path));
        return [
          'title' => $titles[$n],
          'path' => str_replace(WEBROOT_PATH, '', $path)
        ];
      }, glob("$subFolder/*.png"));
      asort($r[$k]['items']);
    }
    return $r;
  }

  function action_default() {
    $this->d['captures'] = $this->twoLevelFolderToArray(WEBROOT_PATH.'/captures/pageModules');
  }

}