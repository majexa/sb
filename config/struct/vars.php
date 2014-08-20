<?php

return [
  'showItemsOnMap'           => [
    'type'   => 'array',
    'title'  => '��������',
    'fields' => [
      'page'  => [
        'title' => '������',
        'type'  => 'page'
      ],
      'dummy' => [
        'title' => 'dummy'
      ]
    ]
  ],
  'hideOnlineStatusUsers'    => [
    'title'      => '�� ���������� � ������ ������-�������������',
    'type'       => 'fieldList',
    'fieldsType' => 'user'
  ],
  'layout'                   => [
    'title'  => '����������',
    'fields' => [
      'pageTitleFormat'   => [
        'title'   => '��� ����������� ��������� � ���� TITLE',
        'type'    => 'select',
        'default' => 1,
        'options' => [
          1 => '�������� ����� � ��� ��������',
          2 => '��� �������� � �������� �����',
        ]
      ],
      'enableShareButton' => [
        'title' => '�������� ������ "����������"',
        'type'  => 'bool'
      ]
    ]
  ],
  'rating'                   => [
    'title'                => '�������',
    'fields'               => [
      'ratingVoterType'      => [
        'title'   => '��� �����������',
        'type'    => 'select',
        'options' => [
          'simple' => '���������� ����� ����� ����������',
          'auth'   => '���������� ����� ����� ������������� ������������',
          'level'  => '���������� ����� ����� ������������ � ������� ���� ����'
        ]
      ],
      'maxStarsN'            => [
        'title' => '������������ ���������� ���� ��� �����������. ������������ � ��� ������, ���� ������������ ��� ����������� ��� ����������� �� ������',
        'type'  => 'num'
      ],
      'isMinus'              => [
        'title' => '��������� �����������',
        'type'  => 'bool'
      ],
      'allowVotingLogForAll' => [
        'title' => '��������� �������� ���� ����������� ��� ����',
        'type'  => 'bool'
      ],
      'grade'                => [
        'title' => '��������� ������ (������ ��� ���� � ��������������� ��������������)',
        'type'  => 'header'
      ],
      'gradeEnabled'         => [
        'title' => '������ ��������',
        'type'  => 'bool'
      ],
      'gradeBegin'           => [
        'type' => 'header'
      ],
      'gradeSetPeriod'       => [
        'title'   => '������ �� ��������� �������� ������������ ������',
        'type'    => 'select',
        'options' => [
          86400    => '�����',
          259200   => '3 ���',
          604800   => '������',
          1209600  => '2 ������',
          2592000  => '�����',
          5184000  => '2 ������',
          7776000  => '3 ������',
          15552000 => '6 �������',
          31104000 => '���',
        ]
      ],
      'gradeSetDay'          => [
        'title'   => '���� ������ ��� ���������� ������ (�����: 4 ����)',
        'type'    => 'select',
        'options' => Arr::filterByKeys(Misc::weekdays(), [1, 6, 7]),
      ],
      'grade5percent'        => [
        'title' => '% �� ���� ������� �� ��������� ������, ���������� 5 ������',
        'type'  => 'num'
      ],
      'grade4percent'        => [
        'title' => '% �� ���� ������� �� ��������� ������, ���������� 4 �����',
        'type'  => 'num'
      ],
      'grade3percent'        => [
        'title' => '% �� ���� ������� �� ��������� ������, ���������� 3 �����',
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
    'title'                => '������',
    'fields'               => [
      'on'                      => [
        'title'   => '��������',
        'type'    => 'bool',
        'default' => false,
      ],
      'interval'                => [
        'title'   => '�������� ��� ����� ������ ��� ���������� ������',
        'type'    => 'select',
        'options' => [
          43200    => '12 �����',
          86400    => '�����',
          172800   => '2 �����',
          604800   => '������',
          1209600  => '2 ������',
          2592000  => '�����',
          7776000  => '3 ������',
          15552000 => '6 �������',
          31104000 => '���',
          62208000 => '2 ����',
          93312000 => '3 ����'
        ],
        'default' => 43200
      ],
      'avatars'                 => [
        'title' => '��������� ������ ������ �� ������',
        'type'  => 'bool'
      ],
      'commentsTagsLayer2Level' => [
        'title'   => '������� ��� �������������� ����� <!-- 2-�� ����� --> � ������������',
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
    'title'  => '������: �����',
    'fields' => [
      'level'     => [
        'title' => '�������',
        'type'  => 'num'
      ],
      'maxStarsN' => [
        'title' => '������������ ���������� ���� �� ���',
        'type'  => 'num'
      ]
    ]
  ],
  'plusItemsDefault'         => [
    'title'  => '���������� ������ �� ������',
    'static' => true,
    'fields' => [
      'n' => [
        'title' => '���-�� ������� �� ������� �������, ������� ����� ������ �����',
        'type'  => 'num'
      ],
      't' => [
        'title' => '������� ������� (������)',
        'type'  => 'num'
      ],
      'e' => [
        'title' => '���-�� ������, ���������� � ���������� ���������� n ����� �� t �����',
        'type'  => 'num'
      ]
    ]
  ],
  'commentsPages'            => [
    'title'      => '������� ��� ��������� ������������',
    'type'       => 'fieldList',
    'fieldsType' => 'pageId'
  ],
  'menu'                     => [
    'title'                => '����',
    'fields'               => [
      'useTagsAsSubMenu'  => [
        'title' => '������������ ���� ������� � �������� ����������� ����',
        'type'  => 'bool'
      ],
      'begin'             => ['type' => 'header'],
      'showNullCountTags' => [
        'title' => '���������� ���� ��� �������',
        'type'  => 'bool'
      ]
    ],
    'visibilityConditions' => [
      [
        'headerName'    => 'begin',
        'condFieldName' => 'useTagsAsSubMenu',
        'cond'          => 'v == true',
      ]
    ]
  ],
  'store'                    => [
    'title'  => '�������',
    'fields' => [
      'enable'                => [
        'type'  => 'bool',
        'title' => '�������'
      ],
      'orderControllerSuffix' => [
        'title' => '���������� ������',
        'type'  => 'storeOrderControllerSuffix'
      ],
      'ordersPageId'          => [
        'title' => '������ � ����� �������',
        'type'  => 'pageId'
      ],
      'orderParams'           => [
        'title' => '�������������� ���� ������',
        'type'  => 'storeOrderFields'
      ],
      'orderBehaviors'        => [
        'title'   => '����� ������',
        'type'    => 'multiselect',
        'options' => [
          'sendToAdmins' => '���������� e-mail � ������� ������ <a href="/admin/configManager/vvv/admins">���������������</a>'
        ]
      ],
    ]
  ],
  'userStore'                => [
    'title'                => '���������������� �������',
    'fields'               => [
      'enable' => [
        'title' => '�������',
        'type'  => 'bool'
      ],
      'begin'  => ['type' => 'header'],
      'roles'  => [
        'title' => '���� �������������, ������� ������ � ��������',
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
    'title'                => '�������',
    'fields'               => [
      'enable'             => [
        'title' => '�������',
        'type'  => 'bool'
      ],
      'userInfoBlockType'  => [
        'title'   => '��� ����� � ����������� ������������',
        'type'    => 'select',
        'options' => [
          ''             => '����� + �����������, ���� ����, ������',
          'profileField' => '���� �� ������� + �����������, ���� ����, ������',
        ]
      ],
      'profileFieldBegin'  => ['type' => 'header'],
      'userInfoBlockField' => [
        'title' => '���� ��������� ����� � ����������� ������������',
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
    'title'  => '��������� ���������',
    'fields' => [
      'enable' => $enable
    ]
  ],

];