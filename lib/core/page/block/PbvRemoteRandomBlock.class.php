<?php

class PbvRemoteRandomBlock extends PbvAbstract {

  function _html() {
    $projectNames = [
      'litcult.ru' => ['blockId' => 92],
      'baby-nn.ru' => ['blockId' => 67],
      //'imhonn.ru' => array('blockId' => 67),
      'mctb.ru' => ['blockId' => 43],
      'of.nnov.ru' => ['blockId' => 14]
    ];
    $p = $projectNames[array_rand($projectNames)];
    $p['blockId'];
    return 1;
  }

}
