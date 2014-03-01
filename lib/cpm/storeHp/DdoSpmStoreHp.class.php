<?php

$f = function($v) {
  if (empty($v['v'])) return '';
  $s = $v['title'].': ';
  $s .= Html::select($v['name'], Arr::get($v['v'], 'title', 'id'));
  $prices = $v['o']->items[$v['id']][$v['name'].'_prices'];
  $prices = Auth::get('id') ? $prices : [$prices[0]];
  $prices = Arr::jsObj($prices);
  return '<div class="orderParamsCont">'.$s.'</div>'.
<<<JS
<script>
Ngn.toObj('Ngn.cart.prices');
Ngn.cart.prices.{$v['name']} = $prices;
window.addEvent('domready', function() {
  Ngn.frm.addEvent('change', '{$v['name']}', function() {
    Ngn.cart.setPrices('{$v['name']}');
  });
  Ngn.cart.setPrices('{$v['name']}');
});
</script>
JS;
};

Ddo::addFuncByName('colors2', $f);
Ddo::addFuncByName('size', $f);

class DdoSpmStoreHp extends DdoSpmStore {}
