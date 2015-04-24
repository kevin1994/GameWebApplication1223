<?php
session_start();
include_once("connection.php");

//empty cart by distroying current session
if(isset($_GET["emptycart"]) && $_GET["emptycart"]==1)
{
	$return_url = base64_decode($_GET["return_url"]); //return url
	session_destroy();
	header('Location:'.$return_url);
}

//add item in shopping cart
if(isset($_POST["type"]) && $_POST["type"]=='add')
{
	$products_id 	= filter_var($_POST["products_id"], FILTER_SANITIZE_STRING); //product code
	$products_qty 	= filter_var($_POST["products_qty"], FILTER_SANITIZE_NUMBER_INT); //product code
	$return_url 	= base64_decode($_POST["return_url"]); //return url
	
	//limit quantity for single product
	if($product_qty > 10){
		die('<div align="center">You are not allowed to buy more than 10 of this product<br />Back To Products</a>.</div>');
	}

	//MySqli query - get details of item from db using product code
	$results = $mysqli->query("SELECT products_name, products_price FROM Products WHERE products_id='$products_id' LIMIT 1");
	$obj = $results->fetch_object();
	
	if ($results) { //we have the product info 
		
		//prepare array for the session variable
		$new_product = array(array('name'=>$obj->products_name, 'id'=>$products_id, 'qty'=>$products_qty, 'price'=>$obj->products_price));
		
		if(isset($_SESSION["products"])) //if session is true
		{
			$found = false; //set item to false
			
			foreach ($_SESSION["products"] as $cart_itm) //loop through session array
			{
				if($cart_itm["id"] == $products_id){ //the item exist in array

					$product[] = array('name'=>$cart_itm["name"], 'id'=>$cart_itm["id"], 'qty'=>$products_qty, 'price'=>$cart_itm["price"]);
					$found = true;
				}else{
					//item doesn't exist in the list, just retrive old info and prepare array for session var
					$product[] = array('name'=>$cart_itm["name"], 'id'=>$cart_itm["id"], 'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"]);
				}
			}
			
			if($found == false) //we didn't find item in array
			{
				//add new user item in array
				$_SESSION["products"] = array_merge($product, $new_product);
			}else{
				//found user item in array list, and increased the quantity
				$_SESSION["products"] = $product;
			}
			
		}else{
			//create a new session var if does not exist
			$_SESSION["products"] = $new_product;
		}
		
	}
	
	//redirect back to original page
	header('Location:'.$return_url);
}

//remove item from shopping cart
if(isset($_GET["removeitem"]) && isset($_GET["return_url"]) && isset($_SESSION["products"]))
{
	$products_id 	= $_GET["removeitem"]; //get the product code to remove
	$return_url 	= base64_decode($_GET["return_url"]); //get return url

	
	foreach ($_SESSION["products"] as $cart_itm) //loop through session array var
	{
		if($cart_itm["id"]!=$products_id){ //item does,t exist in the list
			$product[] = array('name'=>$cart_itm["name"], 'id'=>$cart_itm["id"], 'qty'=>$cart_itm["qty"], 'price'=>$cart_itm["price"]);
		}
		
		//create a new product list for cart
		$_SESSION["products"] = $product;
	}
	
	//redirect back to original page
	header('Location:'.$return_url);
}
?>