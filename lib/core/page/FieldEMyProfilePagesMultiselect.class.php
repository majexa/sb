<?php

class FieldEMyProfilePagesMultiselect extends FieldEMultiselect {

  protected function defineOptions() {
    return array_merge(parent::defineOptions(), [
      'options' => db()->selectCol('SELECT id AS ARRAY_KEY, title FROM pages WHERE controller=?', 'myProfile')
    ]);
  }

}
