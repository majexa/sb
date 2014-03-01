<?php

DdFieldCore::registerType('storeHpTags', [
  'dbType' => 'VARCHAR',
  'dbLength' => 255,
  'title' => 'Выбор нескольких тэгов + 2 цены для каждой из них',
  'order' => 230,
  'tags' => true,
  'subFields' => [
    [
      'dbType' => 'TEXT',
      'name' => 'prices'
    ]
  ]
]);

class FieldEStoreHpTags extends FieldEDdTagsMultiselect {

  protected function getTagsValue() {
    return $this->options['value'][0];
  }

  function _html() {
    $name = $this->options['name'];
    $value = $this->options['value'][1];
    return preg_replace_callback(
      '/<label.*data-id="(\d+)".*<\/label>/Uum',
      function($m) use ($name, $value) {
        return $m[0].'<div class="extraPrices">'.
          '<input name="'.$name.'_price1['.$m[1].']" value="'.$value[0][$m[1]].'" title="Розница" class="fld">'.
          '<input name="'.$name.'_price2['.$m[1].']" value="'.$value[1][$m[1]].'" title="Опт" class="fld">'.
          '</div>';
      },
      parent::_html()
    );
  }

}