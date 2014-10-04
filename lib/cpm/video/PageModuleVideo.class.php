<?php

class PageModuleVideo extends PageModuleDd {

  public $title = 'Видео';
  public $oid = 20;

  protected function ddFields() {
    return [
      [
        'title'    => 'Заголовок',
        'name'     => 'title',
        'type'     => 'typoText',
        'required' => true
      ],
      [
        'title' => 'Видео',
        'name'  => 'video',
        'type'  => 'video',
        //'required' => true
      ]
    ];
  }

}
