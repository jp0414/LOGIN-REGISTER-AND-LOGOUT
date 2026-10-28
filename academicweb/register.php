<?php // Include database configuration and start session
require_once 'core/dbConfig.php'; 

// Guest Guard: If user is already authenticated, redirect to the dashboard
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
	<title>Register - Academic Web Tutoring</title>
	<link rel="stylesheet" href="styles.css">
</head>

<body class="auth-body">
	<div class="auth-container">
		<div class="auth-card">
            <!-- Header and registration branding -->
			<div class="auth-header">
				<div class="auth-icon">✏️</div>
				<h1>Create Account</h1>
				<p>Register to Academic Web Tutoring System</p>
			</div>

            <!-- Flash Error Alerts: Displays specific validation error from query string -->
			<?php if (isset($_GET['error'])) { ?>
				<?php if ($_GET['error'] == 'username_taken') { ?>
					<div class="alert alert-danger">Username is already taken. Please choose a different one.</div>
				<?php } elseif ($_GET['error'] == 'email_taken') { ?>
					<div class="alert alert-danger">Email is already registered. Please use a different email.</div>
				<?php } elseif ($_GET['error'] == 'failed') { ?>
					<div class="alert alert-danger">Registration failed. Please try again.</div>
				<?php } ?>
			<?php } ?>

            <!-- User registration form submitting to core/handleForms.php -->
			<form action="core/handleForms.php" method="POST" class="auth-form">
				<div class="form-row">
					<p>
						<label for="firstName">First Name</label>
						<input type="text" name="firstName" id="firstName" placeholder="Juan" required>
					</p>
					<p>
						<label for="lastName">Last Name</label>
						<input type="text" name="lastName" id="lastName" placeholder="Dela Cruz" required>
					</p>
				</div>
				<p>
					<label for="username">Username</label>
					<input type="text" name="username" id="username" placeholder="juandelacruz" required>
				</p>
				<p>
					<label for="email">Email</label>
					<input type="email" name="email" id="email" placeholder="juan@example.com" required>
				</p>
				<p>
					<label for="password">Password</label>
					<input type="password" name="password" id="password" placeholder="Enter a secure password" required>
				</p>
				<p>
					<label for="address">Address</label>
					<input type="text" name="address" id="address" placeholder="123 Main St, City">
				</p>
				<div class="form-row">
					<p>
						<label for="age">Age</label>
						<input type="number" name="age" id="age" placeholder="25" min="1" max="120">
					</p>
					<p>
						<label for="phoneNumber">Phone Number</label>
						<input type="text" name="phoneNumber" id="phoneNumber" placeholder="+63-912-345-6789">
					</p>
				</div>
				<p>
					<input type="submit" name="registerBtn" value="Register" class="btn-full">
				</p>
			</form>

            <!-- Navigation back to login page for existing accounts -->
			<div class="auth-footer">
				<p>Already have an account? <a href="login.php">Login here</a></p>
			</div>
		</div>
	</div>
</body>

</html>
