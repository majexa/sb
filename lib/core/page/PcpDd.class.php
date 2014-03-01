<?php

class FieldEPageOwnerMode extends FieldESelect {

  protected function defineOptions() {
    $options = [
      'title'   => 'Режим владельца страницы',
      'options' => [
        ''       => 'по умолчанию',
        'author' => 'автор записи'
      ]
    ];
    if (Config::getVarVar('userGroup', 'enable')) $options['options']['userGroup'] = 'сообщество';
    return $options;
  }

}

abstract class PcpDd extends Pcp {

  public $editebleContent = true;

  function getProperties() {
    return Arr::append(parent::getProperties(), [
      [
        'name'      => 'strName',
        'title'     => 'Структура',
        'type'      => 'ddStructure',
        'maxlength' => 50,
        'required'  => 1
      ],
      [
        'name'  => 'tplName',
        'title' => 'Имя каталога с шаблонами',
        'help'  => 'оставить пустым, если используются стандартные шаблоны структуры',
        'type'  => 'text'
      ],
      [
        'name'  => 'formTpl',
        'title' => 'Имя шаблона формы',
        'type'  => 'text'
      ],
      [
        'name'  => 'premoder',
        'title' => 'Премодерация',
        'type'  => 'bool'
      ],
      [
        'name'  => 'comments',
        'title' => 'Комментарии',
        'type'  => 'bool'
      ],
      [
        'name'  => 'allowAnonym',
        'title' => 'Разрешить анонимные комментарии',
        'type'  => 'bool',
        'help'  => 'Используется только в том случае, если комментарии включены'
      ],
      [
        'name'  => 'smW',
        'title' => 'Ширина превьюшки',
        'type'  => 'num'
      ],
      [
        'name'  => 'smH',
        'title' => 'Высота превьюшки',
        'type'  => 'num'
      ],
      [
        'name'  => 'mdW',
        'title' => 'Ширина уменьшенной копии',
        'type'  => 'num'
      ],
      [
        'name'  => 'mdH',
        'title' => 'Высота уменьшенной копии',
        'type'  => 'num'
      ],
      [
        'name'  => 'showFormOnDefault',
        'title' => 'Показывать форму по умолчанию',
        'type'  => 'bool'
      ],
      [
        'name'    => 'completeRedirectType',
        'title'   => 'Что делать после создания/удаления/изменения/и т.п. записи',
        'help'    => 'Перебрасывать на страницу "page.path/complete" после добавления новой записи. Иначе перенаправляется на реферер',
        'type'    => 'select',
        'options' => [
          'self'         => 'На себя саму',
          'referer'      => 'Редирект на реферер',
          'referer_item' => 'Редирект на реферер или запись (для "edit" и "new")',
          'complete'     => 'Редирект на страницу "complete"'
        ]
      ],
      [
        'name'    => 'editTime',
        'title'   => 'Время редактирования',
        'decsr'   => 'Время допущенное для редактирования пользователем записи относительно времени её создания. (в секундах)',
        'type'    => 'select',
        'options' => [
          0                      => 'не определено',
          60                     => '1 минута',
          60 * 3                 => '3 минуты',
          60 * 5                 => '5 минут',
          60 * 10                => '10 минут',
          60 * 20                => '20 минут',
          60 * 30                => '30 минут',
          60 * 60                => '1 час',
          60 * 60 * 2            => '2 часа',
          60 * 60 * 3            => '3 часа',
          60 * 60 * 6            => '6 часов',
          60 * 60 * 12           => '12 часов',
          60 * 60 * 24           => 'сутки',
          60 * 60 * 24 * 2       => '2 суток',
          60 * 60 * 24 * 3       => '3 суток',
          60 * 60 * 24 * 7       => 'неделя',
          60 * 60 * 24 * 30      => 'месяц',
          60 * 60 * 24 * 30 * 12 => 'год',
          9999999999             => 'очень много',
        ]
      ],
      [
        'name'  => 'titleField',
        'title' => 'Поле которое используется в качестве заголовка для страницы записи',
        'type'  => 'ddFields'
      ],
      [
        'name'  => 'titleField',
        'title' => 'Поле которое используется в качестве заголовка для страницы записи',
        'type'  => 'ddFields'
      ],
      [
        'name' => 'ownerMode',
        'type' => 'pageOwnerMode'
      ],
      [
        'name'  => 'myProfileTitle',
        'title' => 'Заголовок в профиле'
      ]
    ]);
  }

  function getAfterSaveDialogs(PageControllerSettingsForm $oF) {
    $dialogs = [];
    $a = function ($prefix) use (&$dialogs, $oF) {
      $data = $oF->getData();
      //$data['smResizeType'] != $oF->defaultData['smResizeType']
      if (empty($oF->defaultData[$prefix.'W'])) return;
      if (empty($data[$prefix.'W'])) return;
      if (empty($oF->defaultData[$prefix.'H'])) return;
      if (empty($data[$prefix.'H'])) return;
      if ($oF->defaultData[$prefix.'W'] != $data[$prefix.'W'] or
        $oF->defaultData[$prefix.'H'] != $data[$prefix.'H']
      ) {
        $dialogs[] = [
          'cls'     => 'Ngn.PartialJob.Dialog',
          'options' => [
            'autostart' => true,
            'pjOptions' => [
              'url'           => Tt()->getPath(1).'/ddImages/'.O::get('Req')->params[2].'?a=json_resize'.ucfirst($prefix).'Images',
              'requestParams' => [
                'w' => $data[$prefix.'W'],
                'h' => $data[$prefix.'H']
              ]
            ]
          ]
        ];
      }
    };
    $a('sm');
    $a('md');
    return empty($dialogs) ? false : $dialogs;
  }

}