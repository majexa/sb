<?php

class PageModuleBehaviorSliceAddress extends PageModuleBehaviorAbstract {

  function action($pageId, $node) {
    // Текстовый слайс адреса
    DbModelCore::create('slices', [
      'id' => 'address_'.$pageId,
      'pageId' => $pageId,
      'title' => 'Адрес',
      'type' => 'text'
    ]);
  }  

}