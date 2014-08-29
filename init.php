<?php

if (file_exists(SITE_PATH.'/config/constants/sb.php')) require SITE_PATH.'/config/constants/sb.php';
define('SB_PATH', __DIR__);
define('CORE_PAGE_MODULES_DIR', 'cpm');
define('CORE_PAGE_MODULES_PATH', SB_PATH.'/lib/'.CORE_PAGE_MODULES_DIR);
define('SITE_PAGE_MODULES_DIR', 'spm');
define('SITE_PAGE_MODULES_PATH', SITE_LIB_PATH.'/'.SITE_PAGE_MODULES_DIR);
Ngn::addBasePath(SB_PATH, 3);
Ngn::addBasePath(NGN_ENV_PATH.'/layout', 2);
//setConstant('SITE_SET', 'basic');
//Ngn::addBasePath(SB_PATH.'/siteSet/'.SITE_SET, 4);
Sflm::$absBasePaths['sb'] = __DIR__.'/static';
Sflm::$absBasePaths['cpm'] = __DIR__.'/lib/cpm';
O::replaceInjection('DefaultRouter', 'SbRouter');