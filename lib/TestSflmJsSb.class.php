<?php

class TestSflmJsSb extends NgnTestCase {

  function test() {
    Sflm::$frontend = 'default';
    Sflm::clearCache();
    Sflm::flm('js')->addLib('sb');
    Sflm::flm('js')->store();
    $v2 = Sflm::flm('js')->version();
    File::replace(Sflm::$absBasePaths['sb'].'/js/Ngn.frm.Page.js', '// -- check --', '// -- che --');
    $contains0 = (bool)strstr(file_get_contents(Sflm::$absBasePaths['sb'].'/js/Ngn.frm.Page.js'), '// -- che --');
    in_array('sd/js/Ngn.frm.Page.js', Sflm::flm('js')->getPaths());
    $contains = (bool)strstr(Sflm::flm('js')->code(), '// -- che --');
    Sflm::flm('js')->store();
    $contains2 = (bool)strstr(file_get_contents(Sflm::flm('js')->cacheFile()), '// -- che --');
    $v3 = Sflm::flm('js')->version();
    File::replace(Sflm::$absBasePaths['sb'].'/js/Ngn.frm.Page.js', '// -- che --', '// -- check --');
    $this->assertTrue($contains0, 'File does not contain new string');
    $this->assertTrue($contains, 'Code does not contain new string');
    $this->assertTrue($contains2, 'Cached file does not contain new string');
    $this->assertTrue($v2 < $v3, "Version not changed after one of included files has changed");
  }

}
