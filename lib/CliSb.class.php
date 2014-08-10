<?php

class CliSb extends CliHelpArgs {

  //function __construct($argv, array $options = []) {
  //}

  function prefix() {
    return false;
  }

  function getClasses() {
    return [
      [
        'class' => 'CliPmi',
        'name' => 'pmi'
      ]
   ];
  }

  protected function _runner() {
    return 'sb';
  }

}