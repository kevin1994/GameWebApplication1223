<?php
session_start();
require 'connection.php';

    if(isset($_POST['login_address'], $_POST['login_password']))
    {
        $login_email = $_POST['login_email'];
        $login_pass = $_POST['login_password'];

        $login_email = stripslashes($login_email);
        $login_pass = stripslashes($login_email);

        $login_email = mysql_real_escape_string($login_email);
        $login_pass = mysql_real_escape_string($login_email);

        $check = "SELECT * FROM Login WHERE email='$login_email' AND Password='$login_pass'";
        $result = mysql_query($check);

        $count=mysql_num_rows($result);

        if($count==1)
        {
            
                $_SESSION['login_email'] = $login_email;
                $_SESSION['login_pass'] = $login_pass;
                header("Location: register.php");
        }
        else
        {
            echo 'Wrong Email or Password';
        }
    }
?>

<html>

<head>
	<title> Slashed Games | Login </title>
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

         <form method='post' action='index.php' width='100%'>
    
            <div class="mainform">
                <br>
                <input type='text' name="login_address" placeholder='Username'/>
                 <br>
                 <br>
                 <br>
                <input type='password' name="login_password" placeholder='Password' />
                 <br>
                 <br>
                 <br>
                <input type='submit'  name="login_submit"  value='Sign In' style= color:#848482; background-color:#F4F4F4 </>
             </div>
         </form>
       
		</div>
		</div>
	</div>


</body>

</html>