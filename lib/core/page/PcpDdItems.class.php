<?php

class PcpDdItems extends PcpDd {

  public $title = 'Записи';

  function getProperties() {
    return Arr::append(parent::getProperties(), [
      [
        'name' => 'order', 
        'title' => 'Сортировка', 
        'type' => 'ddOrder'
      ],
      [
        'name' => 'manualOrder',
        'title' => 'включить ручную сортировку',
        'type' => 'bool'
      ],
      [
        'name' => 'dateField', 
        'title' => 'Поле даты', 
        'type' => 'ddFields'
      ],
      [
        'name' => 'smResizeType',
        'title' => 'Тип создания превьюшек',
        'type' => 'select',
        'options' => [
          '' => 'по умолчанию',
          'resize' => 'обрезание',
          'resample' => 'вписывание'
        ]
      ],
      // ---------------------------------------
      [
        'name' => 'futureItems', 
        'title' => 'Записи в будующем', 
        'help' => 'Фильтр по умолчанию. В качестве даты используются следующие 2 поля', 
        'type' => 'bool'
      ],
      [
        'name' => 'dateFieldBegin', 
        'title' => 'Поле даты начала события', 
        'type' => 'ddDateFields'
      ], 
      [
        'name' => 'dateFieldEnd', 
        'title' => 'Поле даты окончания события', 
        'type' => 'ddDateFields'
      ],
      // ---------------------------------------        
      [
        'name' => 'n', 
        'title' => 'Выводить по', 
        'type' => 'select',
        'options' => [
          '' => 'по умолчанию', 3=>3, 5=>5, 10=>10, 15=>15, 20=>20, 30=>30, 40=>40, 50=>50, 100=>100, 200=>200, 300=>300, 1000=>1000, 9999999 => 'очень много'
        ],
        'default' => 30
      ], 
      [
        'name' => 'tagField', 
        'title' => 'Поле для выборки по тэгу',
        'type' => 'tagFields'
      ], 
      [
        'name' => 'userTagField', 
        'title' => 'Поле для выборки по пользовательскову тэгу', 
        'type' => 'tagFields'
      ], 
      [
        'name' => 'userFilterRequired', 
        'title' => 'Фильтр по пользователю обязателен', 
        'type' => 'bool', 
        'default' => 0
      ], 
      [
        'name' => 'rssTitleField', 
        'title' => 'Поле заголовка RSS', 
        'type' => 'ddFields'
      ], 
      [
        'name' => 'rssDescrField', 
        'title' => 'Поле текста RSS', 
        'type' => 'ddFields'
      ], 
      [
        'name' => 'rssN', 
        'title' => 'Число записей в RSS', 
        'type' => 'num'
      ],
      [
        'name' => 'itemTitle',
        'title' => 'Название одной записи',
        'type' => 'text'
      ],
      [
        'name' => 'createBtnTitle', 
        'title' => 'Заголовок кнопки создания записи'
      ],
      [
        'name' => 'userDataBookmarkTitle',
        'title' => 'Название вкладки в данных пользователя пользователя',
        'type' => 'text'
      ],
      [
        'name' => 'listSlicesType',
        'title' => 'Тип слайсов в списке записей (перед и после списка)',
        'type' => 'listSlicesType',
      ],
      [
        'name' => 'forbidItemPage',
        'title' => 'Запретить отображение страницы записи',
        'type' => 'bool',
      ],
      [
        'name' => 'setItemsOnItem',
        'title' => 'Получать данные для всех записей при открытие страницы одной записи',
        'type' => 'bool'
      ],
      [
        'name' => 'setItemsOnItemLimit',
        'title' => 'Количество получаеммых записей',
        'type' => 'num',
        'default' => 0
      ],
      [
        'name' => 'ddItemsLayout',
        'title' => 'Режим отображения записей',
        'type' => 'select',
        'default' => 'details',
        'options' => [
          'details' => 'Детали',
          'list' => 'Список',
          'tile' => 'Плитка'
        ]
      ],
      [
        'name' => 'showRating',
        'title' => 'Отображать рейтинг',
        'type' => 'bool',
        'default' => false
      ],
      [
        'name' => 'doNotShowItems', 
        'title' => 'Не отображать записи', 
        'type' => 'bool'
      ], 
      [
        'name' => 'doNotShowNitems',
        'title' => 'Не отображать фразу "нет записей"', 
        'type' => 'bool'
      ], 
      [
        'name' => 'orderFields', 
        'title' => 'Поля для сортировки',
        'type' => 'ddMultiFields'
      ],
      [
        'name' => 'showDatePeriodLinks',
        'title' => 'Отображать ссылки периодов (За последние ...)',
        'type' => 'bool',
        'default' => false
      ],
      [
        'name' => 'mysite',
        'title' => 'Используются в качестве записей для Моего сайта',
        'type' => 'bool',
        'default' => false
      ],
      [
        'name' => 'oneItemFromUser',
        'title' => 'Только по одной записи от каждого пользователя',
        'type' => 'bool',
        'default' => false
      ],
      [
        'name' => 'disableItemsCache',
        'title' => 'Выключить кэш записей',
        'type' => 'bool',
        'default' => false
      ]
    ]);
  }

}