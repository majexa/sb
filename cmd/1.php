<?php

require NGN_ENV_PATH.'/authorizeNet/autoload.php';

//PageModule::get('video')

Sflm::setFrontendName();
print (new PageModuleVideo)->create(['title' => 'Видео']);