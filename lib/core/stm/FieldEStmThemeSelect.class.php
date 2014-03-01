<?php

class FieldEStmThemeSelect extends FieldESelect {

  protected function defineOptions() {
    foreach (Dir::dirs(STM_PATH.'/themes') as $v) {
      $theme = include STM_PATH.'/themes/'.$v.'/theme.php';
      $r['ngn:'.$v] = $theme['data']['title'];
    }
    return [
      'options' => array_merge(['' => '— без темы —'], $r)
    ];
  }

}