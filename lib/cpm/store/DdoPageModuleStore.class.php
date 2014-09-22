<?php

Ddo::addFuncByName('buyBtn', function ($v) {
  return <<<HTML
<a href="#" class="btn" data-authorId="{$v['authorId']}"><span>{$v['title']}</span></a>
HTML;
});

class DdoPageModuleStore extends DdoPageModule {
}