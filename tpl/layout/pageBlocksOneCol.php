<?php

print '<div class="body pageBlocks">';
foreach ($d['blocks'] as $b) {
  print '<div class="block pbt_'.$b['type'].
  (!empty($b['class']) ? ' '.$b['class'] : '').
  //(!empty($b['page']['name']) ? ' pageName_'.$b['page']['name'] : '').
  //(!empty($b['name']) ? ' name_'.$b['name'] : '').
  //(!empty($b['page']['module']) ? ' module_'.$b['page']['module'] : '').
  //(!empty($b['page']['settings']['ddItemsLayout']) ?
  //  ' ddil_'.$b['page']['settings']['ddItemsLayout'] : '').
  ' colN'.$b['colN'].'"'.
  (isset($b['id']) ? ' id="block_'.$b['id'].'"' : '').
  'data-global="'.$b['global'].'"'.
  '>'.
  '<div class="bcont"'.$this->enumInlineStyles($b['styles']).'>'.
    $b['html'].
  '</div>'.
  '</div>';
}
print '<div class="clear"><!-- --></div>';
print '</div>';
