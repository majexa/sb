<?php

class StmForm extends Form {

  function getData() {
    $r = parent::getData();
    if (!empty($r['slices'])) foreach ($r['slices'] as $k => $v) if (empty($v['id'])) unset($r['slices'][$k]);
    return $r;
  }

}

class StmDataManager extends DataManagerAbstract {

  /**
   * type - для имен классов
   * subType - для папок
   */
  static $requiredOptions = ['type', 'subType', 'location'];
  
  function __construct(array $options = []) {
    $this->setOptions($options);
    parent::__construct(new StmForm(
      new Fields($this->convertFields()),
      ['filterEmpties' => true]
    ));
  }
  
  /**
   * @return StmData
   */
  function getStmData(array $options = []) {
    // здесь нужен ID
    return O::get(
      'Stm'.ucfirst($this->options['type']).'Data',
      O::get(
        'StmDataSource',
        $this->options['location']
      ),
      array_merge($this->options, $options)
    );
  }
  
  protected function convertFields() {
    $fields = [];
    $names = [];
    foreach ($this->getStmData()->getStructure()->str['fields'] as $v) {
      if (!isset($v['name']) and isset($v['s'])) {
        if (empty($v['p'])) throw new EmptyException('$v[p]');
        $v['name'] = $this->getFieldNameByCssData($v);
        if (in_array($v['name'], $names))
          throw new Exception("{$v['name']} alredy in use. v: ".getPrr($v));
        $names[] = $v['name'];
      }
      $fields[] = $v;
    }
    return $fields;
  }
  
  protected function convertCssDataTformFormat(array $data) {
    $r = [];
    foreach ($data as $v) {
      if (!is_array($v) or !isset($v['s'])) continue;
      $r[$this->getFieldNameByCssData($v)] =
        str_replace(' !important', '', $v['v']);
    }
    return $r;
  }
  
  const PARAMS_DELIMITER = ' & ';
  
  protected function getFieldNameByCssData(array $v) {
    if (!empty($v['pGroup'])) {
      $pp = $v['pGroup'];
    } else {
      // Если параметр групповой, берём для имени только первый
      $params = explode(self::PARAMS_DELIMITER, $v['p']);
      $pp = $params[0];

    }
    return Misc::parseId($v['s']).'_'.Misc::parseId($pp);
  }
  
  protected function getItem($id) {
    return $this->getStmData(['id' => $id])->data;
  }
  
  protected function beforeCreate() {
    $this->data = array_merge([
      'siteSet' => $this->options['siteSet'],
      'design' => $this->options['design']
    ], $this->data);
  }
  
  protected function _create() {
    return $this->getStmData(array_merge($this->options, ['new' => true]))->
      setData($this->data)->save()->id;
  }
  
  protected function beforeUpdate() {
    $o = $this->getStmData(['id' => $this->id]);
    $this->data = array_merge([
      'siteSet' => $o->data['siteSet'],
      'design' => $o->data['design']
    ], $this->data);
  }
  
  protected function _update() {
    $this->getStmData(['id' => $this->id])->setData($this->data)->save();
  }
  
  protected function _delete() {
    $this->getStmData(['id' => $this->id])->delete();
  }
  
  protected function source2formFormat() {
    $this->defaultData = array_merge(
      $this->convertCssDataTformFormat(empty($this->defaultData['cssData']) ?
        [] : $this->defaultData['cssData']
      ),
      empty($this->defaultData['data']) ? [] : $this->defaultData['data']
    );
  }
  
  protected function form2sourceFormat() {
    $r = [];
    foreach ($this->data as $name => $v) {
      if (empty($this->form->getElement($name)->options['s'])) {
        $r['data'][$name] = $v;
      } else {
        $el = $this->form->getElement($name);
        $params = explode(self::PARAMS_DELIMITER, $el['p']);
        if (count($params) > 1) {
          $pGroup = $params[0];
          foreach ($params as $p) {
            $r['cssData'][] = [
              's' => $el['s'],
              'p' => $p,
              'pGroup' => $pGroup,
              'v' => $v.(!empty($el['important']) ? ' !important' : '')
            ];
          }
        } else {
          $r['cssData'][] = [
            's' => $el['s'],
            'p' => $params[0],
            'v' => $v.(!empty($el['important']) ? ' !important' : '')
          ];
        }
      }
    }
    $this->data = $r;
  }
  
  protected function _updateField($id, $fieldName, $value) {
    if ($this->form->fields->isFileType($fieldName)) $value = basename($value); // подстановка путей происходит динамически
    $o = $this->getStmData(['id' => $id]);
    $o->data['data'][$fieldName] = $value;
    $o->save();
  }
  
  function setDataValue($bracketName, $value) {
    BracketName::setValue($this->data['data'], $bracketName, $value);
  }
  
  function updateFileCurrent($file, $fieldName) {
    $attachePath = $this->getAttachePath();
    $filename = $this->getAttacheFilename($fieldName);
    Dir::make($attachePath);
    copy($file, $attachePath.'/'.$filename);
    $this->_updateField($this->options['id'], $fieldName, $filename);
  }
  
  function requestUpdateCurrent() {
    return $this->requestUpdate($this->options['id']);
  }
  
  function getAttacheFolder() {
    return $this->getStmData()->getThemeWpath().'/'.StmCss::FOLDER_NAME.'/'.
      $this->getStmData()->getName();
  }
  
  function getAttachePath() {
    return $this->getStmData()->getThemePath().'/'.StmCss::FOLDER_NAME.'/'.
      $this->getStmData()->getName();
  }
  
  protected function afterUpdate() {
    Sflm::clearCache();
  }
  
}
