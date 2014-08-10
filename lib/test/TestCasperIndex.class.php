<?php

class TestCasperIndex extends ProjectTestCase {

  function test() {
    Casper::run(PROJECT_KEY, [
      'as',
    ]);
  }

}

