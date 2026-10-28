<?php // Include database connection and start session
require_once 'core/dbConfig.php'; 

// Guest Guard: If user is already logged in, redirect them directly to the homepage
if (isset($_SESSION['user_id'])) {
	header("Location: index.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login - Academic Web Tutoring</title>
	<link rel="stylesheet" href="styles.css">
</head>

<body class="auth-body">
	<div class="auth-container">
		<div class="auth-card">
            <!-- Branding and page heading -->
			<div class="auth-header">
				<div class="auth-icon">📚</div>
				<h1>Welcome Back</h1>
				<p>Login to Academic Web Tutoring System</p>
			</div>

            <!-- Flash Alert: Display error if login credentials fail -->
			<?php if (isset($_GET['error']) && $_GET['error'] == 'invalid') { ?>
				<div class="alert alert-danger">Invalid username or password. Please try again.</div>
			<?php } ?>

            <!-- Flash Alert: Display success message after new registration -->
			<?php if (isset($_GET['register']) && $_GET['register'] == 'success') { ?>
				<div class="alert alert-success">Registration successful! You can now login.</div>
			<?php } ?>

            <!-- Flash Alert: Display confirmation upon successful logout -->
			<?php if (isset($_GET['logout']) && $_GET['logout'] == 'success') { ?>
				<div class="alert alert-success">You have been logged out successfully.</div>
			<?php } ?>

            <!-- Login form submitted to core/handleForms.php -->
			<form action="core/handleForms.php" method="POST" class="auth-form">
				<p>
					<label for="username">Username</label>
					<input type="text" name="username" id="username" placeholder="Enter your username" required>
				</p>
				<p>
					<label for="password">Password</label>
					<input type="password" name="password" id="password" placeholder="Enter your password" required>
				</p>
				<p>
					<input type="submit" name="loginBtn" value="Login" class="btn-full">
				</p>
			</form>

            <!-- Navigation link to account registration page -->
			<div class="auth-footer">
				<p>Don't have an account? <a href="register.php">Register here</a></p>
			</div>
		</div>
	</div>
</body>

</html>
