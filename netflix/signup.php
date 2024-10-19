<?php
    if(!empty($_POST)){
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $options = ['cost' => 12];
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT,$options);
        

        $conn = new PDO('mysql:host=localhost;dbname=netflix', 'root', '',);
        $statement = $conn->prepare('INSERT INTO gegevens (email, password) values (:email, :password)');
        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $hash);
        
    }
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IMDFlix</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="netflixLogin">
		<div class="form form--login">
			<form action="" method="post">
				<h2 form__title>Sign up - 30 Days free</h2>


				<?php if(isset($error)): ?>
				<div class="form__error">
					<p>
						Sorry, we can't log you in with that email address and password. Can you try again?
					</p>
				</div>
				<?php endif ?>



				<div class="form__field">
					<label for="Email">Email</label>
					<input type="text" name="email">
				</div>
				<div class="form__field">
					<label for="Password">Password</label>
					<input type="password" name="password">
				</div>

				<div class="form__field">
					<input type="submit" value="Sign up" class="btn btn--primary">	
					
				</div>
			</form>
		</div>
	</div>
</body>
</html>