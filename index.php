<!DOCTYPE html>
<html>
<head>
	<title>MyMoney</title>

	<link rel="icon" href="icon.png" type="image/gif" sizes="16x16">
	<link rel="stylesheet" type="text/css" href="res/css/style.css">
	<link rel="stylesheet" type="text/css" href="res/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
	<div>

		<header>
			<div class="gap">
				<h1><i class="fas fa-wallet"></i>MyMoney</h1>
			</div>
		</header>

		
		<div class="box-login">
			<h2 class="head-login">Login</h2>

			<form action="verifyLogin.php" method="post">
				<input class="input-login" type="text" name="user_login" placeholder="Login">
				<input class="input-login" type="password" name="user_password" placeholder="Password">

				<input class="submit-login" type="submit" value="log in">
			</form>
		</div>
		
	</div>
</body>
</html>