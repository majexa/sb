<?php

$projectFolder = dirname(__DIR__).'/projects/'.$_SERVER['argv'][2];
File::checkExists($projectFolder);
Dir::copy(__DIR__.'/dummyProject', $projectFolder, false);

