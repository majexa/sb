<?php

require NGN_ENV_PATH.'/authorize/autoload.php';

define("AUTHORIZENET_API_LOGIN_ID", "464SMSbY8s7K");
define("AUTHORIZENET_TRANSACTION_KEY", "9U3qqU423g77TECJ");
define("AUTHORIZENET_SANDBOX", false);
$sale           = new AuthorizeNetAIM;
$sale->amount   = "0.1";
//$sale->card_num = '6011000000000012';
$sale->card_num = '5547598380521650';
$sale->exp_date = '07/18';
$response = $sale->authorizeAndCapture();
die2($response);
if ($response->approved) {
  $transaction_id = $response->transaction_id;
  print $transaction_id."\n";
}
print "done\n";
