<?php

class CommentsTags {
  
  static function getTags($level) {
    if ($level >= Config::getVarVar('level', 'commentsTagsLayer2Level'))
      $config = Config::getVar('comments.allowedTags.layer2');
    if (!$config)
      $config = Config::getVar('comments.allowedTags');
    return $config;
  }
  
  static function getToolbarTags($level) {
    $tags = [
      'b' => [
        'title' => 'Жирный',
        'class' => 'bold',
      ],
      'i' => [
        'title' => 'Наклонный',
        'class' => 'italic',
      ],
      'u' => [
        'title' => 'Подчёркнутый',
        'class' => 'underline',
      ],
      's' => [
        'title' => 'Перечёркнутый',
        'class' => 'strike',
      ],
      'quote' => [
        'title' => 'Цитировать',
        'class' => 'quote',
      ],
      'attention' => [
        'title' => 'Выделить',
        'class' => 'attention',
      ],
    ];
    return Arr::filterByKeys($tags, self::getTags($level));
  }
  
}
