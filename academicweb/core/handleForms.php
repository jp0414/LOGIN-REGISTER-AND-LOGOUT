<?php

require_once 'dbConfig.php';
require_once 'models.php';

// ============================================================
// USER AUTHENTICATION HANDLING
// ============================================================

// Register a new user
if (isset($_POST['registerBtn'])) {

	$result = registerUser(
		$pdo,
		$_POST['firstName'],
		$_POST['lastName'],
		$_POST['username'],
		$_POST['password'],
		$_POST['email'],
		$_POST['address'],
		$_POST['age'],
		$_POST['phoneNumber']
	);

	if ($result === true) {
		header("Location: ../login.php?register=success");
		exit();
	} elseif ($result === "username_taken") {
		header("Location: ../register.php?error=username_taken");
		exit();
	} elseif ($result === "email_taken") {
		header("Location: ../register.php?error=email_taken");
		exit();
	} else {
		header("Location: ../register.php?error=failed");
		exit();
	}
}

// Login user
if (isset($_POST['loginBtn'])) {

	$user = loginUser($pdo, $_POST['username'], $_POST['password']);

	if ($user) {
		$_SESSION['user_id'] = $user['user_id'];
		$_SESSION['username'] = $user['username'];
		$_SESSION['first_name'] = $user['first_name'];
		$_SESSION['last_name'] = $user['last_name'];
		header("Location: ../index.php");
		exit();
	} else {
		header("Location: ../login.php?error=invalid");
		exit();
	}
}

// Logout user
if (isset($_GET['logout'])) {
	session_unset();
	session_destroy();
	header("Location: ../login.php?logout=success");
	exit();
}


// ============================================================
// TUTOR HANDLING (PARENT TABLE)
// ============================================================

// Insert a new Tutor
if (isset($_POST['insertTutorBtn'])) {

	$query = insertTutor(
		$pdo,
		$_POST['firstName'],
		$_POST['lastName'],
		$_POST['email'],
		$_POST['phoneNumber'],
		$_POST['specialization'],
		$_POST['hourlyRate'],
		$_SESSION['user_id']
	);

	if ($query) {
		header("Location: ../index.php");
		exit();
	} else {
		echo "Insertion failed";
	}
}

// Edit an existing Tutor
if (isset($_POST['editTutorBtn'])) {

	$query = updateTutor(
		$pdo,
		$_GET['tutor_id'],
		$_POST['firstName'],
		$_POST['lastName'],
		$_POST['email'],
		$_POST['phoneNumber'],
		$_POST['specialization'],
		$_POST['hourlyRate'],
		$_SESSION['user_id']
	);

	if ($query) {
		header("Location: ../index.php");
		exit();
	} else {
		echo "Edit failed";
	}
}

// Delete a Tutor
if (isset($_POST['deleteTutorBtn'])) {

	$query = deleteTutor($pdo, $_GET['tutor_id']);

	if ($query) {
		header("Location: ../index.php");
		exit();
	} else {
		echo "Deletion failed";
	}
}


// ============================================================
// SESSION HANDLING (CHILD TABLE)
// ============================================================

// Insert a new Session under a specific Tutor
if (isset($_POST['insertSessionBtn']) || isset($_POST['insertNewSessionBtn'])) {

	$query = insertSession(
		$pdo,
		$_GET['tutor_id'],
		$_POST['sessionDate'],
		$_POST['startTime'],
		$_POST['endTime'],
		$_POST['subjectTaught'],
		$_POST['notes'],
		$_SESSION['user_id']
	);

	if ($query) {
		header("Location: ../viewtutors.php?tutor_id=" . $_GET['tutor_id']);
		exit();
	} else {
		echo "Insertion failed";
	}
}

// Edit an existing Session
if (isset($_POST['editSessionBtn'])) {

	$query = updateSession(
		$pdo,
		$_GET['session_id'],
		$_POST['sessionDate'],
		$_POST['startTime'],
		$_POST['endTime'],
		$_POST['subjectTaught'],
		$_POST['notes'],
		$_SESSION['user_id']
	);

	if ($query) {
		header("Location: ../viewtutors.php?tutor_id=" . $_GET['tutor_id']);
		exit();
	} else {
		echo "Update failed";
	}
}

// Delete a Session
if (isset($_POST['deleteSessionBtn'])) {

	$query = deleteSession($pdo, $_GET['session_id']);

	if ($query) {
		header("Location: ../viewtutors.php?tutor_id=" . $_GET['tutor_id']);
		exit();
	} else {
		echo "Deletion failed";
	}
}

?>