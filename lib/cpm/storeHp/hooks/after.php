<?php

if (!Auth::get('id')) {
  $this->d['topBtns'][] = [
    'title' => 'Узнать оптовые цены',
    'id' => 'btnShowHiddenPrices'
  ];
}
