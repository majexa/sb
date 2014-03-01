<?php

class PageMetaForm extends Form {

  function __construct($pageId) {
    $this->pageId = $pageId;
    parent::__construct(new Fields([
      [
        'title' => 'Значение тэга «title»',
        'name' => 'title',
      ],
      [
        'title' => 'Тип заголовка страницы',
        'name' => 'titleType',
        'type' => 'select',
        'default' => 'add',
        'options' => [
          'add' => 'Заменять только заголовок страницы в теге «title»',
          'replace' => 'Заменять всё значение тэга «title»',
        ]
      ],
      [
        'title' => 'Описание',
        'name' => 'description',
        'type' => 'textarea',
      ],
      [
        'title' => 'Ключевые слова',
        'name' => 'keywords',
        'type' => 'textarea',
      ],
    ]));
    if (($this->data = DbModelCore::get('pages_meta', $pageId)) !== false)
      $this->setElementsData($this->data->r);
  }
  
  protected function _update(array $data) {
    if ($this->data === false) {
      $data['id'] = $this->pageId;
      DbModelCore::create('pages_meta', $data);
    } else
      DbModelCore::update('pages_meta', $this->data['id'], $data);
  }

}
