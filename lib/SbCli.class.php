<?php

class SbCli extends CliHelpArgsSingle {

  protected $project;

  function __construct() {
    $this->project = $_SERVER['argv'][1];
    parent::__construct(array_slice($_SERVER['argv'], 2), new Cli);
  }

  protected function _runner() {
    return parent::_runner().' '.$this->project;
  }

}