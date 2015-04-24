<?php
session_start();
include_once("connection.php");
?>
<!DOCTYPE html>
<html>
<head>

<title> Slashed Games | Shopping Cart </title>
<link rel="stylesheet" type="text/css" href="style.css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<link href="style2.css" rel="stylesheet" type="text/css">
</head>


<body>
		<div class="header">
		    <div class="nav">
		          <li><a href="login.php">Login</a></li>
		          <li><a href="register.php">Register</a></li>
		    </div>

		    	<img src="banner.png" alt="Slashed Games Banner" title="Slashed Games Banner" />  
			       <div class="navigationbar">
			      	<ul>
			      		<li><a href="index.html">Home</a></li>
			      		<li><a href="XboxOne.php">Xbox One</a></li>
			      		<li><a href="Xbox360.php">Xbox 360</a></li>
			      		<li><a href="PS4.php">PS4</a></li>
			      		<li><a href="PS3.php">PS3</a></li>  
                        		<li><a href="WiiU.php">WiiU</a></li>
			       </div>	   



<div id="products-wrapper">
 <div class="view-cart">
<h2>Shopping Cart</h2>
     
 	<?php
    $current_url = base64_encode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	if(isset($_SESSION["products"]))
    {
	    $total = 0;
        
		echo '<form method="post" action="paypal-express-checkout/process.php">';
		echo '<ul>';
		$cart_items = 0;
		foreach ($_SESSION["products"] as $cart_itm)
        {
           $products_id = $cart_itm["id"];
		   $results = $mysqli->query("SELECT products_name, products_description, products_price FROM Products WHERE products_id='$products_id' LIMIT 1");
		   $obj = $results->fetch_object();
		   
		    echo '<li class="cart-itm">';
			echo '<span class="remove-itm"><a href="cart_update.php?removeitem='.$cart_itm["id"].'&return_url='.$current_url.'">&times;</a></span>';
			echo '<div class="p-price">'.$currency.$obj->products_price.'</div>';
            echo '<div class="product-info">';
			echo '<h3>'.$obj->products_name.' (ID :'.$products_id.')</h3> ';
            echo '<div class="p-qty">Quantity : '.$cart_itm["qty"].'</div>';
            echo '<div>'.$obj->products_description.'</div>';
			echo '</div>';
            echo '</li>';
			$subtotal = ($cart_itm["price"]*$cart_itm["qty"]);
			$total = ($total + $subtotal);

			echo '<input type="hidden" name="item_name['.$cart_items.']" value="'.$obj->products_name.'" />';
			echo '<input type="hidden" name="item_id['.$cart_items.']" value="'.$products_id.'" />';
			echo '<input type="hidden" name="item_desc['.$cart_items.']" value="'.$obj->products_description.'" />';
			echo '<input type="hidden" name="item_qty['.$cart_items.']" value="'.$cart_itm["qty"].'" />';
			$cart_items ++;
			
        }
    	echo '</ul>';
		echo '<span class="check-out-txt">';
		echo '<strong>Total : '.$currency.$total.'</strong>  ';
		echo '</span>';
		echo '</form>';
		
    }else{
		echo 'Your Cart is empty';
	}
	
    ?>
    </div>
</div>
</div> 
</body>
</html>
