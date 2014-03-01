<?php

abstract class StmData extends ArrayAccesseble {
use Options;

  protected function &getArrayLink() {
    return $this->data['data'];
  }

  public $data, $canEdit, $id, $name = 'data';

  /**
   * @var StmDataSource
   */
  public $source;

  function __construct(StmDataSource $source, array $options) {
    $this->source = $source;
    $this->setOptions($options);
    $this->init();
  }

  function init() {
    if (empty($this->options['new'])) {
      Arr::checkEmpty($this->options, 'id');
      $this->id = (int)$this->options['id'];
      $this->data = $this->getData();
    } else {
      $this->id = $this->source->getNextN();
    }
    return $this;
  }

  function getThemePath() {
    return $this->source->getDataPath().'/'.$this->id;
  }

  function getThemeWpath() {
    return $this->source->getDataWpath().'/'.$this->id;
  }

  protected function getFile() {
    return $this->getThemePath().'/'.$this->name.'.php';
  }

  function exists() {
    return file_exists($this->getFile());
  }

  protected function getData() {
    $file = $this->getFile();
    if (!file_exists($file)) return false;
    $r = include $file;
    return $r;
  }

  function setData(array $data) {
    $this->data = $data;
    return $this;
  }

  function setDataValue($k, $v) {
    $this->data['data'][$k] = $v;
    return $this;
  }

  function setCssDataValue($k, $v) {
    $this->data['cssData'][$k] = $v;
    return $this;
  }

  function save() {
    if (!$this->canEdit and !Misc::isGod()) throw new Exception('not allowed to create or change common theme data');
    FileVar::updateVar($this->getFile(), $this->data);
    StmCore::cc();
    return $this;
  }

  function delete() {
    File::delete($this->getFile());
  }

  function getName() {
    if (empty($this->name)) throw new EmptyException('$this->name');
    return $this->name;
  }

  abstract function getStructure();

}

