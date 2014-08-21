<?php

class CtrlPageVStore extends CtrlPageV {
  use DdCrudCtrl;

  static protected function page() {
    return [
      'title' => 'Магазин на диване. Для всей семьи и Дяди Вани'
    ];
  }

  protected function getStrName() {
    return 'store';
  }

  function action_default() {
    $this->d['content'] = $this->ddo()->setItems($this->items()->getItems())->els();
  }

  function action_new2() {
    $im = $this->getIm();
    $this->d['content'] = $im->form->html();
  }

  function afterAction() {
    $this->d['topBtns'] = [
      [
        'title' => 'Создать',
        'class' => 'new',
        'link'  => '/store/new'
      ]
    ];
    $this->d['body'] = PageLayout::autoHtml($this->d['layoutN'], $this->page['id'], $this);
  }

}
