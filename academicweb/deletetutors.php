<?php // Include database connection and helper models
require_once 'core/dbConfig.php'; 
require_once 'core/models.php'; 

// Authentication Guard: Ensure only logged-in users can access this page
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
    <title>Delete Tutor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Top navigation bar showing current logged-in user details and logout option -->
	<div class="top-bar">
		<div class="user-info">
			<span class="user-badge">👤 Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<span class="user-name">(<?php echo htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']); ?>)</span>
		</div>
		<a href="core/handleForms.php?logout=true" class="logout-btn">Logout</a>
	</div>

    <!-- Navigation link back to the dashboard/home -->
    <p><a href="index.php">&larr; Return to Home</a></p>

    <?php 
    // Retrieve tutor_id from query parameters and fetch tutor record
    $tutor_id = $_GET['tutor_id'] ?? null;
    $tutor = getTutorByID($pdo, $tutor_id); 

    // Guard clause: stop execution if the tutor does not exist
    if (!$tutor) {
        echo "<h1>Tutor not found!</h1>";
        exit();
    }
    ?>

    <!-- Confirmation header and cascading deletion warning -->
    <h1 style="color: var(--danger); margin-top: 15px;">Are you sure you want to delete this tutor?</h1>
    <p style="color: var(--text-muted); margin-bottom: 20px;">Warning: Deleting this tutor will also delete all associated tutoring sessions.</p>

    <!-- Tutor details confirmation card -->
    <div class="container">
        <h2>Tutor Name: <?php echo htmlspecialchars($tutor['first_name'] . ' ' . $tutor['last_name']); ?></h2>
        <h2>Email: <?php echo htmlspecialchars($tutor['email']); ?></h2>
        <h2>Phone Number: <?php echo htmlspecialchars($tutor['phone_number'] ?: 'N/A'); ?></h2>
        <h2>Specialization: <?php echo htmlspecialchars($tutor['specialization']); ?></h2>
        <h2>Hourly Rate: $<?php echo htmlspecialchars(number_format($tutor['hourly_rate'], 2)); ?></h2>
        <h2>Date Joined: <?php echo htmlspecialchars($tutor['date_joined']); ?></h2>

        <!-- Audit metadata showing creators and editors -->
		<div class="meta-info">
			<span>📝 Added by: <strong><?php echo htmlspecialchars($tutor['added_by_username'] ?? 'Unknown'); ?></strong></span>
			<span>✏️ Last Updated by: <strong><?php echo htmlspecialchars($tutor['last_updated_by_username'] ?? $tutor['added_by_username'] ?? 'Unknown'); ?></strong></span>
			<span>🕐 Last Updated: <strong><?php echo htmlspecialchars($tutor['last_updated'] ?? 'N/A'); ?></strong></span>
		</div>

        <!-- Form submission triggering tutor deletion -->
        <div class="deleteBtn">
            <form action="core/handleForms.php?tutor_id=<?php echo htmlspecialchars($tutor_id); ?>" method="POST" style="background: none; border: none; padding: 0; box-shadow: none; margin: 0;">
                <input type="submit" name="deleteTutorBtn" value="Delete Tutor">
            </form>
        </div>
    </div>
</body>
</html>