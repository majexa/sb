<?php

class PmiStore extends PmiDd {

  public $title = 'Магазин';

  protected $ddFields = [
    [
      'title'    => 'Название',
      'name'     => 'title',
      'type'     => 'typoText',
      'required' => true
    ],
    [
      'title'    => 'Главное изображение',
      'name'     => 'image',
      'type'     => 'imagePreview',
      'required' => true
    ],
    [
      'title'    => 'Цена',
      'name'     => 'price',
      'type'     => 'price',
      'required' => true
    ],
    [
      'title' => 'Описание',
      'name'  => 'descr',
      'type'  => 'typoTextarea'
    ]
  ];

  protected function createStructure() {
    parent::createStructure();
    if (!$this->sm->items->getItemByField('name', 'orders')) {
      $this->sm->create([
        'title' => 'Заказы',
        'name'  => 'orders',
        'type'  => $this->strType
      ]);
      $fields = [
        [
          'type' => 'col',
          'name' => 'col1',
        ],
        [
          'title' => 'Телефон',
          'type'  => 'phone',
          'name'  => 'phone'
        ],
        [
          'title' => 'Ф.И.О.',
          'type'  => 'text',
          'name'  => 'name'
        ],
        [
          'type' => 'col',
          'name' => 'col2',
        ],
        [
          'title' => 'Комментарий',
          'type'  => 'typoTextarea',
          'name'  => 'comments'
        ],
      ];
      $fm = new DdFieldsManager('orders');
      foreach ($fields as $field) $fm->create($field);
    }
  }

}
