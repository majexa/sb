<?php

define('WEBROOT_PATH', __DIR__);
define('PROJECT_KEY', 'sbpmm');
define('SITE_TITLE', 'Site Builder Page Module Manager');
require dirname(dirname(__DIR__)).'/ngn/init/web-standalone.php';
print (new DefaultRouter(['disableSession' => true]))->dispatch()->getOutput();