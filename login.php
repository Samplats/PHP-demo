<?php

function canLogin($p_email, $p_password){
	if($p_email ==="sam" && $p_password==="12345"){
		return true;
	}
	else{
		return false;
	}
}
//wanneer gaan we pas inloggen
if(!empty($_POST)){
	$email = $_POST['email'];
	$password = $_POST['password'];

	//$result = canLogin($email,$password)
	if (canLogin($email,$password)){
		session_start();
		$_SESSION['loggedin'] = true;
		$_SESSION['email'] = $email;
		//$salt = "312E3REFGREFVDER424444";
		//OK
		//$cookieValue = $email . "," . md5 ($email.$salt);
		//echo $cookieValue;
	//exit();
		//setcookie("login", $cookieValue, time()+60*60*24*30);
		header('Location: index.php');
	}
	else{
		$error =true;
	}
 //echo $email; nu wordt de echo gebruikt als consolelog 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
</head>
<body>
<div class="netflixLogin">
		<div class="form form--login">
			<form action="" method="post">
				<h2 form__title>Sign In</h2>
				<?php if(isset($error) ):?>

				<div class="form__error">
					<p>
						Sorry, we can't log you in with that email address and password. Can you try again?
					</p>
				</div>
				<?php endif;?>
				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" name="email">
				</div>
				<div class="form__field">
					<label for="Password">Password</label>
					<input type="password" name="password">
				</div>

				<div class="form__field">
					<input type="submit" value="Sign in" class="btn btn--primary">	
					<input type="checkbox" id="rememberMe"><label for="rememberMe" class="label__inline">Remember me</label>
				</div>
			</form>
		</div>
	</div>
</body>
</html>