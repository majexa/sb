<?php

class FieldEListSlicesType extends FieldESelect {

  protected function defineOptions() {
    $options['options'] = [
      '' => 'общий для раздела',
    ];
    foreach (O::get('DdFields', $this->form->strName)->getFields() as $v) {
      $options['options']['v_'.$v['name']] =
        'отдельный для каждой выборки по полю «'.$v['title'].'»';
    }
    foreach (O::get('DdFields', $this->form->strName)->getTagFields() as $v) {
      $options['options']['tag_'.$v['name']] =
        'отдельный слайс для каждого тега «'.$v['title'].'»';
    }
    return $options;
  }

}
