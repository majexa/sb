<?php

class StmThemeData extends StmData {

  /**
   * @var StmThemeDataSource
   */
  public $source;

  public $name = 'theme';

  protected function getData() {
    $data = parent::getData();
    Misc::checkEmpty($data);
    $defaultThemeDataFile = STM_DESIGN_PATH.'/'.$data['siteSet'].'/'.$data['design'].'/default.php';
    if (file_exists($defaultThemeDataFile)) $defaultThemeData = include $defaultThemeDataFile;

    if (isset($defaultThemeData)) foreach ($defaultThemeData as $k => $v) {
      $defaultThemeData[$k] = array_merge($defaultThemeData[$k], $v);
    }
    return $data;
  }

  function getStructure() {
    if (empty($this->data['siteSet']) or empty($this->data['design'])) throw new Exception('Wrong data ID='.$this->id.': '.getPrr($this->data));
    return StmCore::getThemeStructure($this->data['siteSet'], $this->data['design']);
  }

}
