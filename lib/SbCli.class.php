<?php

class SbCli extends CliHelpArgs {

  function prefix() {
    Pmi::get('blog')->install();
  }

  //function module() {
  //  new CliHelpResultClass();
  //}

}