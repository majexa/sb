<?php

/**
 * Page Block Structure
 */
abstract class PbsAbstract extends Options2 {
  
  static $cachable = true;
  static $title;
  
  protected $ownPageId;
  protected $fields = [];
  protected $preFields = [];
  public $preParams = [];
  protected $hasJsInit = false;
  protected $type;
  
  protected function addFields(array $fields) {
    $this->fields = Arr::append($this->fields, $fields);
  }
  
  function getType() {
    return preg_replace('/.*_(.*)/', '$1', get_class($this));
  }
  
  protected function init() {
    $this->type = Misc::removePrefix('Pbs', get_class($this));
  }
  
  protected function initFields() {
  }
  
  protected function initPreFields() {
  }
  
  /**
   * Определяет дополнительные параметры, по параметрам в $this->preParams
   */
  protected function initRequiredProperties() {
  }
  
  protected function checkPreParams() {
    foreach ($this->getPreFields() as $v) {
      if ($v['required']) {
        if (empty($this->preParams[$v['name']]))
          throw new Exception(
          "\$this->preParams[{$v['name']}] not defined.".
          " Use ".get_class($this).'::set method to set');
      }
    }
  }
  
  /**
   * Определяет параметры, необходимые для инициализации формы создания блока
   *
   * @param   array
   */
  function setPreParams(array $params) {
    $this->preParams = $params;
    return $this;
  }
  
  function getHiddenParams() {
    return null;
  }
  
  /**
   * Определяет параметры необходимые для формы изменения блока
   * из уже сохраненных 
   *
   * @param   array   Настройки блока
   */
  function setPreParamsBySettings(array $settings) {
    $this->initPreFields();
    if (!$this->preFields) return;
    foreach ($this->preFields as $v) {
      if (!isset($settings[$v['name']]))
        throw new Exception('$settings['.$v['name'].'] not defined');
      $this->preParams[$v['name']] = $settings[$v['name']];
    }
  }
  
  function getPreParams() {
    return $this->preParams;
  }
  
  protected function initDefaultFields() {
    $this->fields[] = [
      'title' => 'Заголовок',
      'name' => 'title'
    ];
    /*
    $this->fields[] = array(
      'title' => 'Размер',
      'name' => 'size',
      'required' => true,
      'type' => 'select',
      'options' => $this->getSizeOptions()
    );
    */    
  }
  
  protected function getSizeOptions() {
    foreach (PageBlockCore::getSizePairs() as $v) {
      $r[$v[0].'-'.$v[1]] = $v[0].' x '.$v[1];
    }
    return $r;
  }
  
  /**
   * Возвращает поля, необходимые для создания блока
   * Если для создания полей требуются дополнительные параметры
   * (определяются в наследуемых классах), то их наличие
   * (назначаются с помощью setPreParams()) будет проверено.
   *
   * @return unknown
   */
  function getFields() {
    if (!empty($this->fields)) return $this->fields;
    $this->checkPreParams();
    $this->initRequiredProperties();
    $this->initDefaultFields();
    $this->initPreParamsHiddenFields();
    $this->initFields();
    if ($this->hasJsInit) {
      $this->fields[] = [
        'type' => 'js',
        'js' => "new Ngn.pb.".ucfirst($this->type)."($({formId}));"
      ];
    }
    return $this->fields;
  }
  
  protected function initPreParamsHiddenFields() {
    foreach ($this->getPreParams() as $k => $v) {
      $this->fields[] = [
        'type' => 'hidden',
        'name' => $k,
        'default' => $v
      ];
    }
  }
  
  function getPreFields() {
    $this->initPreFields();
    return $this->preFields;
  }
  
  function getImageSizes() {
    return Config::getVar('pageBlocks.imageSizes', true);
  }
  
}