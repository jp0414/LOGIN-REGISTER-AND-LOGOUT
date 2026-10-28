<?php // Include database connection and tutor/session models
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 

// Authentication Guard: Ensure user is logged in before accessing tutor editing
if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Tutor</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Top bar showing logged-in user profile details and logout button -->
	<div class="top-bar">
		<div class="user-info">
			<span class="user-badge">👤 Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<span class="user-name">(<?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>)</span>
		</div>
		<a href="core/handleForms.php?logout=true" class="logout-btn">Logout</a>
	</div>

    <!-- Navigation back to the home/tutors list -->
	<p><a href="index.php">&larr; Return to Home</a></p>

	<?php 
	// Retrieve tutor ID from GET query parameter and fetch existing tutor record
	$tutor_id = $_GET['tutor_id'] ?? null;
	$getTutorByID = getTutorByID($pdo, $tutor_id); 

	// Guard clause: stop execution if the tutor does not exist
	if (!$getTutorByID) {
		echo "<h1>Tutor not found!</h1>";
		exit();
	}
	?>

	<h1 style="margin-top: 15px;">Edit Tutor Information</h1>

    <!-- Form for modifying tutor details pre-filled with existing data -->
	<form action="core/handleForms.php?tutor_id=<?php echo htmlspecialchars($tutor_id); ?>" method="POST">
		<p>
			<label for="firstName">First Name</label> 
			<input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($getTutorByID['first_name']); ?>" required>
		</p>
		<p>
			<label for="lastName">Last Name</label> 
			<input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($getTutorByID['last_name']); ?>" required>
		</p>
		<p>
			<label for="email">Email</label> 
			<input type="email" name="email" id="email" value="<?php echo htmlspecialchars($getTutorByID['email']); ?>" required>
		</p>
		<p>
			<label for="phoneNumber">Phone Number</label> 
			<input type="text" name="phoneNumber" id="phoneNumber" value="<?php echo htmlspecialchars($getTutorByID['phone_number']); ?>">
		</p>
		<p>
			<label for="specialization">Specialization</label> 
			<input type="text" name="specialization" id="specialization" value="<?php echo htmlspecialchars($getTutorByID['specialization']); ?>" required>
		</p>
		<p>
			<label for="hourlyRate">Hourly Rate ($/hr)</label> 
			<input type="number" step="0.01" name="hourlyRate" id="hourlyRate" value="<?php echo htmlspecialchars($getTutorByID['hourly_rate']); ?>" required>
		</p>
		<p>
			<input type="submit" name="editTutorBtn" value="Update Tutor">
		</p>
	</form>
</body>
</html>