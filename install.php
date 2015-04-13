<?php

$projectFolder = dirname(__DIR__).'/projects/'.$_SERVER['argv'][2];
File::checkExists($projectFolder);
(new DbSite)->importFile(SB_PATH.'/minimal.sql');
ProjectConfig::updateSubVar('userReg', 'enable', true);
