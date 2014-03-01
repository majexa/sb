<?php

class FieldEStoreOrderControllerSuffix extends FieldESelect {

  protected function defineOptions() {
    return [
      'options' => Arr::get(ClassCore::getDescendants('CtrlPageVStoreOrder'), 'title', 'name')
    ];
  }

}
