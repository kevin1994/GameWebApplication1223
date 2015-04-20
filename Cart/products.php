<?php 
  
    if(isset($_GET['action']) && $_GET['action']=="add"){ 
          
        $id=intval($_GET['Item_id']); 
          
        if(isset($_SESSION['cart'][$id])){ 
              
            $_SESSION['cart'][$id]['quantity']++; 
              
        }else{ 
              
            $sql_s="SELECT * FROM Items 
                WHERE Items_id={$id}"; 
            $query_s=mysql_query($sql_s); 
            if(mysql_num_rows($query_s)!=0){ 
                $row_s=mysql_fetch_array($query_s); 
                  
                $_SESSION['cart'][$row_s['Items_id']]=array( 
                        "quantity" => 1, 
                        "price" => $row_s['price'] 
                    ); 
                  
                  
            }else{ 
                  
                $message="This product id it's invalid!"; 
                  
            } 
              
        } 
          
    } 
  
?>
<h1>Product List</h1> 
    <table> 
        <tr> 
            <th>Name</th> 
            <th>Description</th> 
            <th>Price</th> 
            <th>Action</th> 
        </tr> 
        <tr> 
            <?php 
  
    $sql="SELECT * FROM Items ORDER BY name ASC"; 
    $query=mysql_query($sql); 
      
    while ($row=mysql_fetch_array($query)) { 
          
?> 
        <tr> 
            <td><?php echo $row['Item_name'] ?></td> 
            <td><?php echo $row['description'] ?></td> 
            <td><?php echo $row['price'] ?>$</td> 
            <td><a href="index.php?page=products&action=add&id=<?php echo $row['Item_id'] ?>">Add to cart</a></td> 
        </tr> 
<?php 
          
    } 
  
?>    </table>