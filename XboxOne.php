<?php
session_start();
include_once("connection.php");
?>


<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Slashed Games | Xbox One Games</title>
<link rel="stylesheet" type="text/css" href="styleXboxOne.css" />
<link href="style2.css" rel="stylesheet" type="text/css">
</head>
    
    
    
    
<body>
	<div class="background">
		<div class="header">
		    <div class="nav">
		          <li><a href="login.html">Login</a></li>
		          <li><a href="register.html">Register</a></li>
		    </div>

		    	<img src="banner.png" alt="Slashed Games Banner" title="Slashed Games Banner" >  
			       <div class="navigationbar">
			      	<ul>
			      		<li><a href="index.html">Home</a></li>
			      		<li><a href="XboxOne.php">Xbox One</a></li>
			      		<li><a href="Xbox360.php">Xbox 360</a></li>
			      		<li><a href="PS4.php">PS4</a></li>
			      		<li><a href="PS3.php">PS3</a></li>  
                        		<li><a href="WiiU.php">WiiU</a></li>
			       </div>	      
        </div>
    </div>       
<div id="products-wrapper">
    <h1>Games</h1>
    <div class="products">
    <?php
    //current URL of the Page. cart_update.php redirects back to this URL
	$current_url = base64_encode($url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    
	$results = $mysqli->query("SELECT * FROM Products WHERE Products_console='Xbox One' ");
    if ($results) { 
	
        //fetch results set as object and output HTML
        while($obj = $results->fetch_object())
        {
			echo '<div class="product">'; 
            echo '<form method="post" action="cart_update.php">';
			echo '<div class="product-thumb"><img src="images/'.$obj->products_img.'"\" width=85px height=100px></div>';
            echo '<div class="product-content"><h3>'.$obj->products_name.'</h3>';
            echo '<div class="product-desc">'.$obj->products_description.'</div>';
            echo '<div class="product-info">';
			echo 'Price '.$currency.$obj->products_price.' | ';
            echo 'Qty <input type="text" name="products_qty" value="1" size="3" />';
			echo '<button class="add_to_cart">Add To Cart</button>';
			echo '</div></div>';
            echo '<input type="hidden" name="products_id" value="'.$obj->products_id.'" />';
            echo '<input type="hidden" name="type" value="add" />';
			echo '<input type="hidden" name="return_url" value="'.$current_url.'" />';
            echo '</form>';
            echo '</div>';
        }
    
    }
    ?>
    </div>
    
<div class="shopping-cart">
<h2>Your Shopping Cart</h2>
<?php
if(isset($_SESSION["products"]))
{
    $total = 0;
    echo '<ol>';
    foreach ($_SESSION["products"] as $cart_itm)
    {
        echo '<li class="cart-itm">';
        echo '<span class="remove-itm"><a href="cart_update.php?removeitem='.$cart_itm["id"].'&return_url='.$current_url.'">&times;</a></span>';
        echo '<h3>'.$cart_itm["name"].'</h3>';
        echo '<div class="p-code">Id : '.$cart_itm["id"].'</div>';
        echo '<div class="p-qty">Qty : '.$cart_itm["qty"].'</div>';
        echo '<div class="p-price">Price :'.$currency.$cart_itm["price"].'</div>';
        echo '</li>';
        $subtotal = ($cart_itm["price"]*$cart_itm["qty"]);
        $total = ($total + $subtotal);
    }
    echo '</ol>';
    echo '<span class="check-out-txt"><strong>Total : '.$currency.$total.'</strong> <a href="view_cart.php">Check-out!</a></span>';
	echo '<span class="empty-cart"><a href="cart_update.php?emptycart=1&return_url='.$current_url.'">Empty Cart</a></span>';
}else{
    echo 'Your Cart is empty';
}
?>
</div>
    
</div>
    
    <script src="jQuery.js"></script> 
    <script src="script.js"></script>
</body>
</html>


