<?php

class DdItemsManagerPage extends DdItemsManager {

  /**
   * ID родительского объекта
   *
   * @var integer
   */
  public $pageId;

  function __construct(DdItemsPage $items, DdFormPage $form, array $options = []) {
    parent::__construct($items, $form, $options);
    $this->pageId = $items->pageId;
    Misc::checkEmpty($this->pageId);
    $settings = $items->page->r['settings'];
    $this->imageSizes = Arr::filterEmptiesR(Arr::filterByKeys($settings, array_keys($this->imageSizes)));
    if (!empty($settings['smResizeType'])) $this->smResizeType = $settings['smResizeType'];
  }

  /**
   * Добавляет ID раздела в данные создаваемой записи
   *
   * @param   array   Данные создаваемой записи
   */
  protected function addCreateData() {
    parent::addCreateData();
    $this->data['pageId'] = $this->pageId;
  }

  protected function allIds() {
    return db()->selectCol("SELECT id FROM {$this->items->table} WHERE pageId=?d", $this->items->pageId);
  }

}
