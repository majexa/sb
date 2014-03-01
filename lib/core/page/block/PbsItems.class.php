<?php

class PbsItems extends PbsDdPage {

  static $title = 'Записи';
  
  protected function initFields() {
    $this->fields[] = [
      'name' => 'order',
      'title' => 'Сортировка',
      'type' => 'select',
      'required' => true,
      'options' => DdFieldOptions::order($this->strName)
    ];
    $this->fields[] = [
      'name' => 'listBtnOrder',
      'title' => 'Кнопка «все» ссылается на список записей',
      'type' => 'select',
      'required' => true,
      'options' => [
        'default' => 'в порядке, определенном по умолчанию для раздела',
        'block' => 'в том же порядке что и в блоке',
      ]
    ];
    $this->fields[] = [
      'name' => 'listBtnTitle',
      'title' => 'Заголовок кнопки «все»'
    ];
    $this->fields[] = [
      'name' => 'limit',
      'title' => 'Лимит',
      'type' => 'num',
      'required' => true
    ];
  }
  
}