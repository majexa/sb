<?php

class PbsTags extends PbsDdPage {
  
  static $title = 'Тэги';
  
  protected function initFields() {
    $this->fields[] = [
      'name' => 'tagField',
      'title' => 'Поле тега',
      'type' => 'select',
      'required' => true,
      'options' => Arr::get(
        O::get('DdFields', $this->strName)->getTagFields(),
        'title', 'name'
      )
    ];
    $this->fields[] = [
      'name' => 'showNullCountTags',
      'title' => 'Отображать тэги с нулевым кол-вом записей',
      'type' => 'bool'
    ];
    $this->fields[] = [
      'name' => 'showTagCounts',
      'title' => 'Отображать кол-во записей',
      'type' => 'bool'
    ];
    $this->fields[] = [
      'name' => 'hideSubLevels',
      'title' => 'Скрывать вложенные тэги по-умолчанию',
      'type' => 'bool',
      'default' => true
    ];
    $this->fields[] = [
      'name' => 'showOnlyLeafs',
      'title' => 'Выводить только теги с таписями, но не имеющие вложенных тегов',
      'type' => 'bool',
      'default' => false
    ];
  }

}