<?php

Ddo::addFuncByName('buyBtn', function($v) {
  return <<<HTML
<a href="#" class="btn" data-authorId="{$v['authorId']}"><span>{$v['title']}</span></a>
<div class="cnt"><span id="Cnt{$v['id']}">1</span></div>
<link rel="stylesheet" type="text/css" media="screen, projection" href="/cpm/store/products.css" />
HTML;


});

class DdoSpmStore extends DdoPageSite {}