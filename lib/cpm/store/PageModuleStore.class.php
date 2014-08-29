<?php

class PageModuleStore extends PageModuleDd {

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
      'title'    => 'Категория',
      'name'     => 'category',
      'type'     => 'ddTagsSelect',
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

  protected function prepareNode(array $node) {
    $node = parent::prepareNode($node);
    $node['settings']['smW'] = 150;
    $node['settings']['smH'] = 150;
    return $node;
  }

  protected function createStructure() {
    parent::createStructure();
    db()->importFile(__DIR__.'/dump.sql');
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

  function getPageBlocks() {
    return [[
      'type' => 'tags',
      'settings' => [
        'tagField' => 'category',
        'showTagCounts' => true
      ]
    ]];
  }

}
