<?php

Arr::checkEmpty($_SERVER['argv'], 2);
$name = $_SERVER['argv'][2];
$pageModuleName = 'pageModule'.ucfirst($name);
print `tst proj g test $pageModuleName`;
$capturesRootFolder = NGN_ENV_PATH.'/rumax/web/captures';
Dir::copy("$capturesRootFolder/pageModules/$name", SB_PATH."/web/captures/pageModules/$name");
Dir::remove("$capturesRootFolder/pageModules/$name");
