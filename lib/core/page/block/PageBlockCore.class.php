<?php

class PageBlockCore {

  /**
   * @param   string
   * @return  PbsAbstract
   */
  static function getStructure($type, array $options = []) {
    return O::get(ClassCore::nameToClass('Pbs', $type), $options);
  }

  static function cachable($type) {
    return ClassCore::getStaticProperty(ClassCore::nameToClass('Pbv', $type), 'cachable');
  }

  /**
   * Возвращает массив array(
   *   'blockType' => 'blockTitle',
   *   ...
   * )
   *
   * @return array
   */
  static function getTypeOptions() {
    return array_merge(Html::defaultOption(), ClassCore::getStaticProperties('Pbs', 'title', 'title'));
  }

  static function getTitle($type) {
    return ClassCore::getStaticProperty(ClassCore::nameToClass('Pbs', $type), 'title');
  }

  static function getDynamicBlockModels($ownPageId) {
    return DbModelCore::collection('pageBlocks', DbCond::get()->addF('ownPageId', $ownPageId)->addF('global', ($ownPageId == 0 ? 1 : 0))->setOrder('oid'), DbModelCore::modeObject);
  }

  static protected $blocks = [];

  static function getBlocks($ownPageId, CtrlPage $ctrl = null) {
    if (isset(self::$blocks[$ownPageId])) return self::$blocks[$ownPageId];
    $staticBlocks = new PageModuleStaticBlocks($ctrl);
    $blocks = $staticBlocks->blocks;
    $dynamicBlockModels = self::getDynamicBlockModels($ownPageId);
    $dynamicBlockModels = array_merge($dynamicBlockModels, self::getDynamicBlockModels(0));
    $staticBlocks->processDynamicBlockModels($dynamicBlockModels);
    if ($ctrl) $ctrl->processDynamicBlockModels($dynamicBlockModels);
    foreach ($dynamicBlockModels as $oPBM) $blocks[] = self::getBlockHtmlData($oPBM, $ctrl);
    $blocks = Arr::sortByOrderKey($blocks, 'oid');
    return self::$blocks[$ownPageId] = $blocks;
  }

  /**
   * Возвращает массив с данными блока и сгенерированным HTML
   *
   * @param   integer   PageID
   * @return  array
   */
  static function getDynamicBlocks($ownPageId, CtrlPage $oController = null) {
    $blocks = [];
    foreach (self::getDynamicBlockModels($ownPageId) as $oPBM) {
      $blocks[] = self::getBlockHtmlData($oPBM, $oController);
    }
    return $blocks;
  }

  static function getBlockHtmlData(DbModel $oPBM, CtrlPage $oController = null) {
    try {
      $pbv = O::get(ClassCore::nameToClass('Pbv', $oPBM['type']), $oPBM, $oController);
      $class = ClassCore::nameToClass('Pbvug', $oPBM['type']);
      if (isset($oController) and $oController->userGroup and class_exists($class)) {
        LogWriter::str('dd', $oPBM['id']);
        $block = O::get($class, $pbv)->getData();
      }
      else {
        $block = $pbv->getData();
      }
    } catch (Exception $e) {
      $block['colN'] = $oPBM['colN'];
      if (IS_DEBUG) $block['html'] = Err::getErrorText($e);
    }
    return $block;
  }

  static function getStaticBlockHtmlData($className, $type, CtrlPage $ctrl) {
    return Arr::getValueByKey(O::get(ClassCore::nameToClass('Pmsb', $className), $ctrl)->blocks, 'type', $type);
  }

  static function getBlocksByCol($ownPageId, $colN, $oController = null) {
    return Arr::filterByValue(self::getBlocks($ownPageId, $oController), 'colN', $colN);
  }

  static function getDynamicBlocksCount($ownPageId) {
    return count(self::getDynamicBlocks($ownPageId));
  }

  /**
   * Нормализирует номера колонок блоков из имеющихся
   *
   * @param   array     $blocks
   * @param   integer   Всего колонок
   */
  static function sortBlocks(array $blocks, $colsN) {
    $blocksByCols = [];
    foreach ($blocks as $b) {
      // Если номер колонки блока больше, возможного кол-ва колонок, 
      // помещаем его в последнюю
      if ($b['colN'] > $colsN) $b['colN'] = $colsN;
      // Если равен нулю - в первею
      elseif ($b['colN'] == 0) $b['colN'] = 1;
      $blocksByCols[$b['colN']][] = $b;
    }
    return $blocksByCols;
  }

  static function cc($id) {
    NgnCache::c()->remove('pageBlock_'.$id);
  }

  static function updateColN($id, $colN, $oid = 0) {
    db()->query('UPDATE pageBlocks SET colN=?d WHERE id=?d', $colN, $id);
    self::cc($id);
  }

  static function delete($id) {
    DbModelCore::delete('pageBlocks', $id);
  }

  /**
   * @param  array  Array of filter arrays
   * @return array
   */
  static function deleteCollection(array $filters = null) {
    $cond = DbCond::get();
    if ($filters) foreach ($filters as $filter) $cond->addF($filter[0], $filter[1]);
    foreach (db()->ids('pageBlocks', $cond) as $id) DbModelCore::delete('pageBlocks', $id);
  }

  // ------------------- Duplicates -------------------

  static function createGlobalBlocksDuplicates($ownPageId) {
    if (PageLayoutN::get(0) != PageLayoutN::get($ownPageId)) throw new Exception("Global and pageId=$ownPageId page layouts must be equals");
    foreach (self::getDynamicBlocks(0) as $v) {
      DbModelCore::create('pageBlocks', [
        'ownPageId' => $ownPageId,
        'colN'      => $v['colN'],
        'type'      => 'duplicate',
        'global'    => 0,
        'settings'  => ['duplicateBlockId' => $v['id']]
      ]);
    }
    Settings::set('globalBocksAdded'.$ownPageId, true);
  }

  static function deleteDuplicateBlocks($ownPageId) {
    foreach (Arr::filterByValue(self::getDynamicBlocks($ownPageId), 'type', 'duplicate') as $v) {
      self::delete($v['id']);
    }
    Settings::delete('globalBocksAdded'.$ownPageId, true);
  }

  static function globalBlocksDuplicatesExists($ownPageId) {
    return Settings::get('globalBocksAdded'.$ownPageId);
  }

}