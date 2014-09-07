<?php

Ddo::addFuncByName('buyBtn', function ($v) {
  return <<<HTML
<a href="#" class="btn" data-authorId="{$v['authorId']}"><span>{$v['title']}</span></a>
HTML;
  // <div class="cnt"><span id="Cnt{$v['id']}">1</span></div>
});

class DdoPageModuleStore extends DdoPageModule {
}