<?php

class PbsKwix extends PbsAbstract {

  static $title = 'Раздвигающиеся панели';
  
  protected function initFields() {
    $this->fields = [
      [
        'type' => 'col2'
      ],
      [
        'title' => 'Высота блока',
        'name' => 'height',
        'type' => 'pixels',
        'required' => true
      ],
      [
        'type' => 'col2'
      ],
      [
        'title' => 'Цвет заголовка',
        'name' => 'titleColor',
        'type' => 'color'
      ],
      [
        'type' => 'col2'
      ],
      [
        'title' => 'Цвет текста',
        'name' => 'textColor',
        'type' => 'color'
      ],
      [
        'type' => 'col2'
      ],
      [
        'title' => 'Размер заголовка',
        'name' => 'titleSize',
        'type' => 'fontSize'
      ],
      [
        'type' => 'col2'
      ],
      [
        'title' => 'Размер текст',
        'name' => 'textSize',
        'type' => 'fontSize'
      ],
      [
        'type' => 'header'
      ],
      [
        'title' => 'Панели',
        'name' => 'items', 
        'type' => 'fieldSet', 
        'fields' => [
          [
            'title' => 'Ссылка',
            'name' => 'link',
            'type' => 'pageLink',
            'required' => true
          ],
          [
            'title' => 'Заголовок',
            'name' => 'title',
            'type' => 'textarea',
            'required' => true
          ],
          [
            'title' => 'Текст',
            'name' => 'text',
            'type' => 'textarea'
          ],
          [
            'title' => 'Фоновое изображение',
            'name' => 'bg',
            'type' => 'image'
          ],
        ]
      ]
    ];
  }

}