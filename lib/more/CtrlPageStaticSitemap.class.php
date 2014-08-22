<?php

class CtrlPageStaticSitemap extends CtrlPageStatic {

  static function page() {
    return [
      'title' => 'Site Map'
    ];
  }

  function action_default() {
    $this->d['content'] = (new PagesTreeTpl(-1))->html();
    $this->d['body'] = PageLayout::autoHtml($this->d['layoutN'], $this->page['id'], $this);
  }

}