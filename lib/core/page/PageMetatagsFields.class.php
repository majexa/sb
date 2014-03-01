<?php

class PageMetatagsFields extends Fields {
  
  function __construct() {
    parent::__construct([
      [
        'name' => 'title',
        'title' => 'Title',
        'type' => 'text',
      ],
      [
        'name' => 'description',
        'title' => 'Текст',
        'type' => 'textarea'
      ],
      [
        'name' => 'keywrods',
        'title' => 'Текст',
        'type' => 'textarea'
      ],
    ]);
  }
  
}
