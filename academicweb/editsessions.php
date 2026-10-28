<?php // Include database configuration and helper models
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 

// Authentication Guard: Ensure user is logged in before editing
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
	<title>Edit Tutoring Session</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Top bar displaying active logged-in user and logout option -->
	<div class="top-bar">
		<div class="user-info">
			<span class="user-badge">👤 Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<span class="user-name">(<?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>)</span>
		</div>
		<a href="core/handleForms.php?logout=true" class="logout-btn">Logout</a>
	</div>

	<?php 
	// Retrieve session ID and parent tutor ID from query parameters
	$session_id = $_GET['session_id'] ?? null;
	$tutor_id = $_GET['tutor_id'] ?? null;
	$session = getSessionByID($pdo, $session_id); 

	// Guard clause: stop execution if session does not exist
	if (!$session) {
		echo "<h1>Session not found!</h1>";
		exit();
	}
	?>

    <!-- Navigation link back to the parent tutor's sessions list -->
	<p><a href="viewtutors.php?tutor_id=<?php echo htmlspecialchars($tutor_id); ?>">&larr; Return to Tutoring Sessions</a></p>

	<h1 style="margin-top: 15px;">Edit Tutoring Session</h1>

    <!-- Form for editing session details pre-populated with existing database values -->
	<form action="core/handleForms.php?session_id=<?php echo htmlspecialchars($session_id); ?>&tutor_id=<?php echo htmlspecialchars($tutor_id); ?>" method="POST">
		<p>
			<label for="subjectTaught">Subject Taught</label> 
			<input type="text" name="subjectTaught" id="subjectTaught" value="<?php echo htmlspecialchars($session['subject_taught']); ?>" required>
		</p>
		<p>
			<label for="sessionDate">Session Date</label> 
			<input type="date" name="sessionDate" id="sessionDate" value="<?php echo htmlspecialchars($session['session_date']); ?>" required>
		</p>
		<p>
			<label for="startTime">Start Time</label> 
			<input type="time" name="startTime" id="startTime" value="<?php echo htmlspecialchars($session['start_time']); ?>" required>
		</p>
		<p>
			<label for="endTime">End Time</label> 
			<input type="time" name="endTime" id="endTime" value="<?php echo htmlspecialchars($session['end_time']); ?>" required>
		</p>
		<p>
			<label for="notes">Notes</label> 
			<input type="text" name="notes" id="notes" value="<?php echo htmlspecialchars($session['notes']); ?>">
		</p>
		<p>
			<input type="submit" name="editSessionBtn" value="Update Session">
		</p>
	</form>
</body>
</html>