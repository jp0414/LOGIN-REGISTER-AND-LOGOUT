<?php // Include database configuration and data helper functions
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 

// Authentication Guard: Ensure user is signed in before allowing deletion
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
	<title>Delete Tutoring Session</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Top bar displaying active session account and logout action -->
	<div class="top-bar">
		<div class="user-info">
			<span class="user-badge">👤 Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<span class="user-name">(<?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>)</span>
		</div>
		<a href="core/handleForms.php?logout=true" class="logout-btn">Logout</a>
	</div>

	<?php 
	// Extract session ID and parent tutor ID from query parameters
	$session_id = $_GET['session_id'] ?? null;
	$tutor_id = $_GET['tutor_id'] ?? null;
	$session = getSessionByID($pdo, $session_id); 

	// Guard clause: stop if the session does not exist in the database
	if (!$session) {
		echo "<h1>Session not found!</h1>";
		exit();
	}
	?>

    <!-- Navigation back to the specific tutor's sessions page -->
	<p><a href="viewtutors.php?tutor_id=<?php echo htmlspecialchars($tutor_id); ?>">&larr; Return to Tutoring Sessions</a></p>

    <!-- Deletion confirmation prompt -->
	<h1 style="color: var(--danger); margin-top: 15px;">Are you sure you want to delete this tutoring session?</h1>
	
    <!-- Session details container for user review -->
	<div class="container">
		<h2>Subject Taught: <?php echo htmlspecialchars($session['subject_taught']); ?></h2>
		<h2>Session Date: <?php echo htmlspecialchars($session['session_date']); ?></h2>
		<h2>Time: <?php echo htmlspecialchars(date("g:i A", strtotime($session['start_time'])) . ' - ' . date("g:i A", strtotime($session['end_time']))); ?></h2>
		<h2>Tutor: <?php echo htmlspecialchars($session['tutor_name']); ?></h2>
		<h2>Notes: <?php echo htmlspecialchars($session['notes'] ?: 'None'); ?></h2>

        <!-- Audit metadata showing creators and editors -->
		<div class="meta-info">
			<span>📝 Added by: <strong><?php echo htmlspecialchars($session['added_by_username'] ?? 'Unknown'); ?></strong></span>
			<span>✏️ Last Updated by: <strong><?php echo htmlspecialchars($session['last_updated_by_username'] ?? $session['added_by_username'] ?? 'Unknown'); ?></strong></span>
			<span>🕐 Last Updated: <strong><?php echo htmlspecialchars($session['last_updated'] ?? 'N/A'); ?></strong></span>
		</div>

        <!-- Form submission triggering deletion handler -->
		<div class="deleteBtn">
			<form action="core/handleForms.php?session_id=<?php echo htmlspecialchars($session_id); ?>&tutor_id=<?php echo htmlspecialchars($tutor_id); ?>" method="POST" style="background: none; border: none; padding: 0; box-shadow: none; margin: 0;">
				<input type="submit" name="deleteSessionBtn" value="Delete Session">
			</form>
		</div>	
	</div>
</body>
</html>
