<?php
$currency = '£'; //Currency symbol or code

$db_username = 'team4';
$db_password = 'group4';
$db_name = 'db_team4';
$db_host = '194.81.104.22';
$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);

$PayPalMode 			= 'sandbox'; // sandbox or live
$PayPalApiUsername 		= 'slashedgamesgroup-facilitator_api1.gmail.com'; //PayPal API Username
$PayPalApiPassword 		= 'V6BHDDQWZN3BBEDG'; //Paypal API password
$PayPalApiSignature 	= 'AN7c5UD8Vt8VloKcH4PYzQJ3NYNTApLmbO7PfRXSiAMWUNMizli.HLHd'; //Paypal API Signature
$PayPalCurrencyCode 	= 'GBP'; //Paypal Currency Code
$PayPalReturnURL 		= 'http://www.computing.northampton.ac.uk/~2027c_4/groupwork/paypal-express-checkout/process.php'; //Point to process.php page
$PayPalCancelURL 		= 'http://www.computing.northampton.ac.uk/~2027c_4/groupwork/paypal-express-checkout/cancel_url.html'; //Cancel URL if user clicks cancel
?>