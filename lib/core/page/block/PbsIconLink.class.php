<?php

class PbsIconLink extends PbsAbstract {

  static $title = 'Иконка с ссылкой';

  protected function initFields() {
    $this->fields[] = [
      'title' => 'Ссылка',
      'name' => 'url',
      'type' => 'pageLink',
      'required' => true
    ];
    $this->fields[] = [
      'title' => 'Изображение',
      'name' => 'image',
      'type' => 'image',
      'required' => true
    ];
    $this->fields[] = [
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'textarea',
      //'type' => 'wisiwig',
    ];
  }

}