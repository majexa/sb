<?php

class FieldEPageController extends FieldERequestFieldsSelect {

  protected function defineOptions() {
    return [
      'title' => 'Контроллер',
      'options' => (
        ['' => '— '.LANG_NOTHING_SELECTED.' —'] +
        PageControllersCore::getTitles()
      ),
      'requestedNames' => ['settings'],
      'action' => 'ajax_controllerRequiredFields'
    ];
  }

}