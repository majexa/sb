<?php

class Slice {

  /**
   * @return DbModelSlices
   */
  static function getOrCreate(array $data) {
    if (($r = DbModelCore::get('slices', $data['id'])) !== false) {
      return $r;
    }
    DbModelCore::create('slices', $data);
    return DbModelCore::get('slices', $data['id']);
  }
  
  static function html($id, $title, array $options = []) {
    $type = empty($options['type']) ? 'html' : $options['type'];
    return self::getOrCreate([
      'id' => $id,
      'title' => $title,
      'type' => $type,
      'absolute' => !empty($options['absolute'])
    ])->setProp('allowAdmin', !empty($options['allowAdmin']))->html();
  }
  
  static function deleteByPageId($pageId) {
    array_map(function($id) {
      DbModelCore::delete('slices', $id);
    }, db()->ids('slices', 'pageId=?d', $pageId));
  }
  
  /**
   * Сохраняет позицию для абсолютных слайсов
   * 
   * @param   string  ID лайса
   * @param   array   array('x' => .123, 'y' => 123)
   */
  static function savePos($id, array $s) {
    Arr::checkEmpty($s, ['x', 'y']);
    $data = StmCore::getCurrentThemeData();
    $k = Arr::getKeyByValue($data->data['data']['slices'], 'id', $id);
    $data->data['data']['slices'][$k]['x'] = $s['x'];
    $data->data['data']['slices'][$k]['y'] = $s['y'];
    $data->save();
  }
  
}