<?php

$module = R::get('options')['module'];
$pageModuleName = 'uiPageModule'.ucfirst($module);
print `tst proj g test $pageModuleName`;
$capturesRootFolder = NGN_ENV_PATH.'/rumax/web/captures';
Dir::copy("$capturesRootFolder/pageModules/$module", SB_PATH."/web/captures/pageModules/$module");
Dir::remove("$capturesRootFolder/pageModules/$module");
