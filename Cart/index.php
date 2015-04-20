<?php 
    session_start(); 
    require("includes/connection.php"); 
    if(isset($_GET['page'])){ 
          
        $pages=array("Items", "cart"); 
          
        if(in_array($_GET['page'], $pages)) { 
              
            $_page=$_GET['page']; 
              
        }else{ 
              
            $_page="Items"; 
              
        } 
          
    }else{ 
          
        $_page="Items"; 
          
    } 
?>
<h1>Cart</h1> 
<?php 
  
    if(isset($_SESSION['cart'])){ 
          
        $sql="SELECT * FROM Items WHERE Item_id IN ("; 
          
        foreach($_SESSION['cart'] as $id => $value) { 
            $sql.=$id.","; 
        } 
          
        $sql=substr($sql, 0, -1).") ORDER BY name ASC"; 
        $query=mysql_query($sql); 
        while($row=mysql_fetch_array($query)){ 
              
        ?> 
            <p><?php echo $row['Item_name'] ?> x <?php echo $_SESSION['cart'][$row['Item_id']]['quantity'] ?></p> 
        <?php 
              
        } 
    ?> 
        <hr /> 
        <a href="index.php?page=cart">Go to cart</a> 
    <?php 
          
    }else{ 
          
        echo "<p>Your Cart is empty. Please add some products.</p>"; 
          
    } 
  
?>