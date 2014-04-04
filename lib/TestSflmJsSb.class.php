<?php

class TestSflmJsSb extends NgnTestCase {

  function test() {
    Sflm::setFrontend('default');
    Sflm::clearCache();
    Sflm::frontend('js')->addLib('sb');
    Sflm::frontend('js')->store();
    $v2 = Sflm::frontend('js')->version();
    File::replace(Sflm::$absBasePaths['sb'].'/js/Ngn.frm.Page.js', '// -- check --', '// -- che --');
    $contains0 = (bool)strstr(file_get_contents(Sflm::$absBasePaths['sb'].'/js/Ngn.frm.Page.js'), '// -- che --');
    in_array('sd/js/Ngn.frm.Page.js', Sflm::frontend('js')->getPaths());
    $contains = (bool)strstr(Sflm::frontend('js')->code(), '// -- che --');
    Sflm::frontend('js')->store();
    $contains2 = (bool)strstr(file_get_contents(Sflm::frontend('js')->cacheFile()), '// -- che --');
    $v3 = Sflm::frontend('js')->version();
    File::replace(Sflm::$absBasePaths['sb'].'/js/Ngn.frm.Page.js', '// -- che --', '// -- check --');
    $this->assertTrue($contains0, 'File does not contain new string');
    $this->assertTrue($contains, 'Code does not contain new string');
    $this->assertTrue($contains2, 'Cached file does not contain new string');
    $this->assertTrue($v2 < $v3, "Version not changed after one of included files has changed");
  }

}
