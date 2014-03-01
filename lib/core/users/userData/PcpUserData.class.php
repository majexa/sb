<?php

class PcpUserData extends Pcp {
  
  public $title = 'Данные пользователя';
  
  function getProperties() {
    return Arr::append(parent::getProperties(), [
      [
        'name' => 'wallEnable',
        'title' => 'Стена включена',
        'type' => 'bool'
      ],
      [
        'name' => 'wallTitle',
        'title' => 'Название вкладки стены',
        'type' => 'text'
      ],      
      [
        'name' => 'commentsEnable',
        'title' => 'Комментарии включены',
        'type' => 'bool'
      ],
      [
        'name' => 'answersEnable',
        'title' => 'Ответы включены',
        'type' => 'bool'
      ],
      [
        'name' => 'myProfilePageId',
        'title' => 'Раздел профиля',
        'type' => 'myProfilePagesSelect',
        'required' => true
      ],
      [
        'name' => 'profilesPageId',
        'title' => 'Раздел записей профиля',
        'type' => 'profilesPagesSelect',
        'required' => true
      ],
      [
        'name' => 'ddItemsPageIds',
        'title' => 'Используемые разделы с записями',
        'type' => 'itemsPagesMultiselect'
      ],
      [
        'name' => 'userItemsLimit',
        'title' => 'Лимит записей',
        'type' => 'num'
      ],
      [
        'name' => 'allowEmail',
        'title' => 'Разрешить отправку e-mail\'ов',
        'type' => 'bool',
        'default' => true
      ],
      [
        'name' => 'allowAnonimEmail',
        'title' => 'Разрешить отправку e-mail\'ов от анонимных пользователей',
        'type' => 'bool',
        'default' => true
      ],
      [
        'name' => 'showRegDate',
        'title' => 'Отображать дату регистрации',
        'type' => 'bool',
        'default' => true
      ]
    ]);
  }
  
}