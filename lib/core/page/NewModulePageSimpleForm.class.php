<?php

class NewModulePageSimpleForm extends NewModulePageFormBase {

  protected function _getFields() {
    $fields = parent::_getFields();
    unset($fields[0]['options']['']);
    $fields[0]['type'] = 'imagedRadio';
    return $fields;
  }
  
}
