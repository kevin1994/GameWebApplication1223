<?php
session_start();
include_once("connection.php");
include_once("paypal.class.php");

$paypalmode = ($PayPalMode=='sandbox') ? '.sandbox' : '';

if($_POST) //Post Data received from product list page.
{
	//Other important variables like tax, shipping cost
	$TotalTaxAmount 	= 2.58;  //Sum of tax for all items in this order. 
	$HandlingCost 		= 2.00;  //Handling cost for this order.
    
	//we need 4 variables from product page Item Name, Item Price, Item Number and Item Quantity.
	//Please Note : People can manipulate hidden field amounts in form,
	//In practical world you must fetch actual price from database using item id. 
	//eg : $ItemPrice = $mysqli->query("SELECT item_price FROM products WHERE id = Product_Number");
	$paypal_data ='';
	$ProductsTotal = 0;
	
    foreach($_POST['products_name'] as $key=>$itmname)
    {
        $products_id 	= filter_var($_POST['products_id'][$key], FILTER_SANITIZE_STRING); 
		
		$results = $mysqli->query("SELECT products_name, products_description, products_price FROM Products WHERE products_id='$products_id' LIMIT 1");
		$obj = $results->fetch_object();
		
        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($obj->products_name);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($_POST['products_id'][$key]);
        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($obj->products_price);		
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='. urlencode($_POST['item_qty'][$key]);
        
		// item price X quantity
        $subtotal = ($obj->products_price*$_POST['item_qty'][$key]);
		
        //total price
        $ProductsTotal = $ProductsTotal + $subtotal;
		
		//create items for session
		$paypal_product['items'][] = array('itm_name'=>$obj->products_name,
											'itm_price'=>$obj->products_price,
											'itm_code'=>$obj->products_id, 
											'itm_qty'=>$_POST['item_qty'][$key]
											);
    }
				
	//Grand total including all tax, insurance, shipping cost
	$GrandTotal = ($ProductsTotal + $TotalTaxAmount + $HandlingCost);
	
								
	$paypal_product['oCosts'] = array('tax_total'=>$TotalTaxAmount, 
								'handling_cost'=>$HandlingCost, 
								'grand_total'=>$GrandTotal);
	
	//create session array for later use
	$_SESSION["paypal_products"] = $paypal_product;
	
	//Parameters for SetExpressCheckout, which will be sent to PayPal
	$padata = 	'&METHOD=SetExpressCheckout'.
				'&RETURNURL='.urlencode($PayPalReturnURL ).
				'&CANCELURL='.urlencode($PayPalCancelURL).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				$paypal_data.				
				'&NOSHIPPING=0'. //set 1 to hide buyer's shipping address, in-case products that does not require shipping
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ProductsTotal).
				'&PAYMENTREQUEST_0_TAXAMT='.urlencode($TotalTaxAmount).
				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($HandlingCost).
				'&PAYMENTREQUEST_0_AMT='.urlencode($GrandTotal).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
				'&LOCALECODE=GB'. //PayPal pages to match the language on your website.
				'&CARTBORDERCOLOR=FFFFFF'. //border color of cart
				'&ALLOWNOTE=1';
		
		//We need to execute the "SetExpressCheckOut" method to obtain paypal token
		$paypal= new MyPayPal();
		$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
		//Respond according to message we receive from Paypal
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{
				//Redirect user to PayPal store with Token received.
			 	$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
				header('Location: '.$paypalurl);
		}
		else
		{
			//Show error message
			echo '<b>Error: </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
		}

}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"]))
{
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	
	$token = $_GET["token"];
	$payer_id = $_GET["PayerID"];
	
	//get session variables
	$paypal_product = $_SESSION["paypal_products"];
	$paypal_data = '';
	$ProductsTotal = 0;

    foreach($paypal_product['items'] as $key=>$p_item)
    {		
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='. urlencode($p_item['itm_qty']);
        $paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($p_item['itm_price']);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($p_item['itm_name']);
        $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($p_item['itm_code']);
        
		// item price X quantity
        $subtotal = ($p_item['itm_price']*$p_item['itm_qty']);
		
        //total price
        $ProductsTotal = ($ProductsTotal + $subtotal);
    }


	$padata = 	'&TOKEN='.urlencode($token).
				'&PAYERID='.urlencode($payer_id).
				'&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
				$paypal_data.
				'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ProductsTotal).
				'&PAYMENTREQUEST_0_HANDLINGAMT='.urlencode($paypal_product['oCosts']['handling_cost']).
				'&PAYMENTREQUEST_0_AMT='.urlencode($paypal_product['oCosts']['grand_total']).
				'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);

	//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
	$paypal= new MyPayPal();
	$httpParsedResponseAr = $paypal->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
	
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{

			echo '<h3>Transaction Successful</h3>';
			echo 'Your Transaction ID: '.urldecode($httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
    }
        else
        {
            echo '<h3>Transaction Failed</h3>';
            echo '<b>Error: </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
        }
			
}
    
?>
