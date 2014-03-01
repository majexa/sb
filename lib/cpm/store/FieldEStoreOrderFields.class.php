<?php

class FieldEStoreOrderFields extends FieldEMultiselect {

  protected function defineOptions() {
    return array_merge(parent::defineOptions(), [
        'options' => Arr::get(array_filter(O::get('DdFields', DbModelCore::get('pages', 3)->r['strName'])->fields, function ($v) {
            return !DdTags::isTree($v['type']);
          }), 'title', 'name')
      ]);
  }

}