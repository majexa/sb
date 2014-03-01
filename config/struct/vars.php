<?php

return [
  'showItemsOnMap'           => [
    'type'   => 'array',
    'title'  => 'Страницы',
    'fields' => [
      'page'  => [
        'title' => 'Раздел',
        'type'  => 'page'
      ],
      'dummy' => [
        'title' => 'dummy'
      ]
    ]
  ],
  'hideOnlineStatusUsers'    => [
    'title'      => 'Не показывать в списке онлайн-пользователей',
    'type'       => 'fieldList',
    'fieldsType' => 'user'
  ],
  'layout'                   => [
    'title'  => 'Оформление',
    'fields' => [
      'pageTitleFormat'   => [
        'title'   => 'Вид отображения заголовка в теге TITLE',
        'type'    => 'select',
        'default' => 1,
        'options' => [
          1 => 'Название сайта — Имя страницы',
          2 => 'Имя страницы — Название сайта',
        ]
      ],
      'enableShareButton' => [
        'title' => 'Включить кнопку "Поделиться"',
        'type'  => 'bool'
      ]
    ]
  ],
  'rating'                   => [
    'title'                => 'Рейтинг',
    'fields'               => [
      'ratingVoterType'      => [
        'title'   => 'Тип голосования',
        'type'    => 'select',
        'options' => [
          'simple' => 'Голосовать может любой посетитель',
          'auth'   => 'Голосовать может любой авторизованый пользователь',
          'level'  => 'Голосовать может любой пользователь с уровнем выше нуля'
        ]
      ],
      'maxStarsN'            => [
        'title' => 'Максимальное количество звёзд для голосования. Используется в том случае, если используется тип голосования без ограничений по уровню',
        'type'  => 'num'
      ],
      'isMinus'              => [
        'title' => 'Минусовое голосование',
        'type'  => 'bool'
      ],
      'allowVotingLogForAll' => [
        'title' => 'Разрешить просмотр лога голосований для всех',
        'type'  => 'bool'
      ],
      'grade'                => [
        'title' => 'Настройки оценки (только для типа с авторизованными пользователями)',
        'type'  => 'header'
      ],
      'gradeEnabled'         => [
        'title' => 'Оценка включена',
        'type'  => 'bool'
      ],
      'gradeBegin'           => [
        'type' => 'header'
      ],
      'gradeSetPeriod'       => [
        'title'   => 'Период по истечении которого выставляется оценка',
        'type'    => 'select',
        'options' => [
          86400    => 'сутки',
          259200   => '3 дня',
          604800   => 'неделя',
          1209600  => '2 недели',
          2592000  => 'месяц',
          5184000  => '2 месяца',
          7776000  => '3 месяца',
          15552000 => '6 месяцев',
          31104000 => 'год',
        ]
      ],
      'gradeSetDay'          => [
        'title'   => 'День недели для назначения оценки (время: 4 утра)',
        'type'    => 'select',
        'options' => Arr::filterByKeys(Misc::weekdays(), [1, 6, 7]),
      ],
      'grade5percent'        => [
        'title' => '% от всех записей за указанный период, набирающих 5 баллов',
        'type'  => 'num'
      ],
      'grade4percent'        => [
        'title' => '% от всех записей за указанный период, набирающих 4 балла',
        'type'  => 'num'
      ],
      'grade3percent'        => [
        'title' => '% от всех записей за указанный период, набирающих 3 балла',
        'type'  => 'num'
      ],
    ],
    'visibilityConditions' => [
      [
        'headerName'    => 'gradeBegin',
        'condFieldName' => 'gradeEnabled',
        'cond'          => 'v == true',
      ]
    ]
  ],
  'level'                    => [
    'title'                => 'Уровни',
    'fields'               => [
      'on'                      => [
        'title'   => 'Включены',
        'type'    => 'bool',
        'default' => false,
      ],
      'interval'                => [
        'title'   => 'Интервал для сбора данных для назначения уровня',
        'type'    => 'select',
        'options' => [
          43200    => '12 часов',
          86400    => 'сутки',
          172800   => '2 суток',
          604800   => 'неделя',
          1209600  => '2 недели',
          2592000  => 'месяц',
          7776000  => '3 месяца',
          15552000 => '6 месяцев',
          31104000 => 'год',
          62208000 => '2 года',
          93312000 => '3 года'
        ],
        'default' => 43200
      ],
      'avatars'                 => [
        'title' => 'Добавлять иконку уровня на аватар',
        'type'  => 'bool'
      ],
      'commentsTagsLayer2Level' => [
        'title'   => 'Уровень для дополнительных тэгов <!-- 2-го ранга --> в комментариях',
        'type'    => 'select',
        'options' => [
          1  => 1,
          2  => 2,
          3  => 3,
          4  => 4,
          5  => 5,
          6  => 6,
          7  => 7,
          8  => 8,
          9  => 9,
          10 => 10,
        ],
      ],
    ],
    'visibilityConditions' => [
      [
        'headerName'    => 'begin',
        'condFieldName' => 'on',
        'cond'          => 'v == true',
      ]
    ]
  ],
  'levelStars'               => [
    'title'  => 'Уровни: звёзды',
    'fields' => [
      'level'     => [
        'title' => 'Уровень',
        'type'  => 'num'
      ],
      'maxStarsN' => [
        'title' => 'Максимальное количество звёзд за раз',
        'type'  => 'num'
      ]
    ]
  ],
  'plusItemsDefault'         => [
    'title'  => 'Присвоение плюсов за записи',
    'static' => true,
    'fields' => [
      'n' => [
        'title' => 'Кол-во записей за единицу времени, которое будет давать плюсы',
        'type'  => 'num'
      ],
      't' => [
        'title' => 'Единица времени (секунд)',
        'type'  => 'num'
      ],
      'e' => [
        'title' => 'Кол-во плюсов, получаемое в результате добавления n работ за t время',
        'type'  => 'num'
      ]
    ]
  ],
  'commentsPages'            => [
    'title'      => 'Разделы для последних комментариев',
    'type'       => 'fieldList',
    'fieldsType' => 'pageId'
  ],
  'menu'                     => [
    'title'                => 'Меню',
    'fields'               => [
      'useTagsAsSubmenu'  => [
        'title' => 'Использовать тэги раздела в качестве подразделов меню',
        'type'  => 'bool'
      ],
      'begin'             => ['type' => 'header'],
      'showNullCountTags' => [
        'title' => 'Показывать тэги без записей',
        'type'  => 'bool'
      ]
    ],
    'visibilityConditions' => [
      [
        'headerName'    => 'begin',
        'condFieldName' => 'useTagsAsSubmenu',
        'cond'          => 'v == true',
      ]
    ]
  ],
  'store'                    => [
    'title'  => 'Магазин',
    'fields' => [
      'enable'                => [
        'type'  => 'bool',
        'title' => 'Включен'
      ],
      'orderControllerSuffix' => [
        'title' => 'Контроллер заказа',
        'type'  => 'storeOrderControllerSuffix'
      ],
      'ordersPageId'          => [
        'title' => 'Раздел с базой заказов',
        'type'  => 'pageId'
      ],
      'orderParams'           => [
        'title' => 'Дополнительные поля заказа',
        'type'  => 'storeOrderFields'
      ],
      'orderBehaviors'        => [
        'title'   => 'Опции заказа',
        'type'    => 'multiselect',
        'options' => [
          'sendToAdmins' => 'Отправлять e-mail с текстом заказа <a href="/admin/configManager/vvv/admins">администраторам</a>'
        ]
      ],
    ]
  ],
  'userStore'                => [
    'title'                => 'Пользовательский магазин',
    'fields'               => [
      'enable' => [
        'title' => 'Включен',
        'type'  => 'bool'
      ],
      'begin'  => ['type' => 'header'],
      'roles'  => [
        'title' => 'Роли пользователей, имеющих доступ к магазину',
        'type'  => 'roleMultiselect'
      ],
    ],
    'visibilityConditions' => [
      [
        'headerName'    => 'begin',
        'condFieldName' => 'enable',
        'cond'          => 'v == true',
      ]
    ]
  ],
  'profile'                  => [
    'title'                => 'Профиль',
    'fields'               => [
      'enable'             => [
        'title' => 'Включен',
        'type'  => 'bool'
      ],
      'userInfoBlockType'  => [
        'title'   => 'Тип блока с информацией пользователя',
        'type'    => 'select',
        'options' => [
          ''             => 'Логин + изображение, если есть, кнопки',
          'profileField' => 'Поле из профиля + изображение, если есть, кнопки',
        ]
      ],
      'profileFieldBegin'  => ['type' => 'header'],
      'userInfoBlockField' => [
        'title' => 'Поле заголовка блока с информацией пользователя',
        'type'  => 'profileFields'
      ],
    ],
    'visibilityConditions' => [
      [
        'headerName'    => 'profileFieldBegin',
        'condFieldName' => 'userInfoBlockType',
        'cond'          => 'v == "profileField"',
      ]
    ]
  ],
  'privMsgs'                 => [
    'title'  => 'Приватные сообщения',
    'fields' => [
      'enable' => $enable
    ]
  ],

];