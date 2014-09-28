<?php

require NGN_ENV_PATH.'/authorizeNet/autoload.php';

//07/17
//4741653001608332

define('AUTHORIZENET_API_LOGIN_ID', '464SMSbY8s7K');
define('AUTHORIZENET_TRANSACTION_KEY', '9U3qqU423g77TECJ');
define('AUTHORIZENET_SANDBOX', false);
$sale           = new AuthorizeNetAIM;
$sale->amount   = '0.1';
//$sale->card_num = '5547598380521650';
//$sale->exp_date = '07/18';
$sale->card_num = '4741653001608332';
$sale->exp_date = '07/17';
$response = $sale->authorizeAndCapture();
die2($response);
