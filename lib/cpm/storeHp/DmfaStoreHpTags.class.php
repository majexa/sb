<?php

class DmfaStoreHpTags extends DmfaDdTagsMultiselect {

  function source2formFormat($v, $k, $item) {
    return [$v, isset($item[$k.'_prices']) ? $item[$k.'_prices'] : []];
  }

  function elBeforeCreateUpdate(FieldEAbstract $el) {
    $p1 =& $this->dm->form->req->p[$el['name'].'_price1'];
    $p2 =& $this->dm->form->req->p[$el['name'].'_price2'];
    $p1 = Arr::filterEmptiesR($p1);
    $p2 = Arr::filterEmptiesR($p2);
    $this->dm->data[$el['name'].'_prices'] = [
      array_map(['Misc', 'price'], $p1),
      array_map(['Misc', 'price'], $p2)
    ];
  }

}