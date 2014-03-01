<?php

interface ProcessDynamicPageBlock {

  /**
   * @param array Array of DbModelPageBlocks
   */
  function processDynamicBlockModels(array &$blockModels);

}
