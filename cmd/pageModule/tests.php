<?php

print `tst proj g test pageModule%`;
$capturesRootFolder = NGN_ENV_PATH.'/rumax/web/captures';
Dir::copy("$capturesRootFolder/pageModules", SB_PATH.'/web/captures/pageModules');
Dir::remove("$capturesRootFolder/pageModules");
