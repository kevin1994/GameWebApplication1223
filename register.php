<?php
require 'connection.php';

if (isset($_POST['reg_firstname'], $_POST['reg_surname'], $_POST['reg_email'], $_POST['reg_address'], $_POST['reg_telephone'], $_POST['reg_password']))
    
{
    $first_name = $_POST['reg_firstname'];
    $surname = $_POST['reg_surname'];
    $email = $_POST['reg_email'];
    $address = $_POST['reg_address'];
    $telephone_number = $_POST['reg_telephone'];
    $password = $_POST['reg_password'];
    
    
    
    $sql = "INSERT INTO Login (`first_name`, `surname`, `email`, `address`, `telephone_number`, `password`)
    VALUES ('$first_name', '$surname', '$email', '$address', '$telephone_number', '$password')";
    
    if(mysql_query($sql, $conn)) 
        {
        echo "Registration Complete";
        }
        else 
            {
            echo "Error: " . $sql . "<br>" . mysql_error($conn);
            }
}
?>


<html>

<head>
	<title> Slashed Games: Register </title>
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>	

<body>
	<div class="background">
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
       
				    <div class="container">
			         <form method="post" action="register.php" width="50%">
			    
			            <div class="mainform">
			                <br>
			                <input type="text" name="reg_email" placeholder="Email Address"/>
			                 <br>
			                 <br>
			                 <br>
					        <input type="text" name="reg_password" placeholder="Password"/>
			                <br>
                            <br>
                            <br>
                            <input type="text" name="reg_firstname" placeholder="First Name"/>
			                <br>
                            <br>
                            <br>
                            <input type="text" name="reg_surname" placeholder="Surname"/>
                            <br>
                            <br>
                            <br>
                            <input type="text" name="reg_address" placeholder="Address"/>
                            <br>
                            <br>
                            <br>
                            <input type="text" name="reg_telephone" placeholder="Telephone Number"/>
                            
			                <input type="submit"  name="reg_submit"  value="Register" style= color:#848482; background-color:#F4F4F4 </>
			             </div>
			         </form>
			        </div>       
</div>
</div>
</div>

</body>
</html> 