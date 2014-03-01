<?php

$projFolder = dirname(__DIR__).'/projects/'.$_SERVER['argv'][2];
File::checkExists($projFolder);
Dir::copy(__DIR__.'/dummyProject', $projFolder, false);