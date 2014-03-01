<?php

class FieldEPriviliges extends FieldESelect {

  protected function defineOptions() {
    return [
      'options' => [
        ''           => '-',
        'edit'       => 'Редактирование раздела',
        'create'     => 'Создание записей в разделе',
        'sub_edit'   => 'Редактирование саб-объектов',
        'sub_create' => 'СОздание саб-объектов'
      ]
    ];
  }

}

class UserPrivForm extends Form {

  function __construct() {
    parent::__construct(new Fields([
      [
        'title'     => 'Привелегии пользователей',
        'name'      => 'userPrivs',
        'type'      => 'fieldSet',
        'fields'    => [
          [
            'title'    => 'Пользователь',
            'type'     => 'user',
            'name'     => 'userId',
            'required' => true
          ],
          [
            'title'    => 'Привелегия',
            'type'     => 'priviliges',
            'name'     => 'type',
            'required' => true
          ],
        ],
        'jsOptions' => ['addTitle' => 'Добавть привелегию']
      ]
    ]));
    $this->setElementsData(['userPrivs' => db()->select('SELECT * FROM privs WHERE pageId=?d', $this->pageId)]);
  }

}

class PageUserPrivForm extends UserPrivForm {

  protected $pageId;

  function __construct($pageId) {
    $this->pageId = $pageId;
    parent::__construct();
  }

  protected function _update(array $data) {
    db()->query("DELETE FROM privs WHERE pageId=?d", $this->pageId);
    foreach ($data['userPrivs'] as $v) {
      $v['pageId'] = $this->pageId;
      db()->create('privs', $v);
    }
  }

}

class CtrlAdminPagePriv extends CtrlAdminPagesBase {

  static $properties = [
    'title'  => 'Привилегии',
    'descr'  => 'Привилегии пользователей',
    'onMenu' => true,
    'order'  => 40
  ];

  function action_json_editPage() {
    $this->json['title'] = 'Радактирование привилегий раздела «'.$this->page['title'].'»';
    return $this->jsonFormActionUpdate(new PageUserPrivForm($this->page['id']));
  }

}