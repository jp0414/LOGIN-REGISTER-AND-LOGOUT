<?php

	// ============================================================
	// USERS - Registration, Login, and Fetch Functions
	// ============================================================

	// Register a new user
	function registerUser($pdo, $firstName, $lastName, $username, $password, $email, $address, $age, $phoneNumber) {
		// Check if username already exists
		$checkSql = "SELECT user_id FROM users WHERE username = ?";
		$checkStmt = $pdo->prepare($checkSql);
		$checkStmt->execute([$username]);
		if ($checkStmt->fetch()) {
			return "username_taken";
		}

		// Check if email already exists
		$checkSql = "SELECT user_id FROM users WHERE email = ?";
		$checkStmt = $pdo->prepare($checkSql);
		$checkStmt->execute([$email]);
		if ($checkStmt->fetch()) {
			return "email_taken";
		}

		// Hash the password before storing
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		// Handle optional integer/empty fields
		$cleanAge = (!empty($age) || $age === '0' || $age === 0) ? (int)$age : null;
		$cleanAddress = !empty($address) ? trim($address) : null;
		$cleanPhone = !empty($phoneNumber) ? trim($phoneNumber) : null;

		$sql = "INSERT INTO users (first_name, last_name, username, password, email, address, age, phone_number) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$firstName, $lastName, $username, $hashedPassword, $email, $cleanAddress, $cleanAge, $cleanPhone]);

		if ($executeQuery) {
			return true;
		}
		return false;
	}

	// Login user - verify credentials
	function loginUser($pdo, $username, $password) {
		$sql = "SELECT * FROM users WHERE username = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$username]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($user && password_verify($password, $user['password'])) {
			return $user;
		}
		return false;
	}

	// Get user by ID
	function getUserByID($pdo, $user_id) {
		$sql = "SELECT * FROM users WHERE user_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$user_id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Get username by user_id (for display purposes)
	function getUsernameByID($pdo, $user_id) {
		if (!$user_id) return 'Unknown';
		$sql = "SELECT username FROM users WHERE user_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$user_id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row ? $row['username'] : 'Unknown';
	}


	// ============================================================
	// TUTORS (PARENT TABLE) - CRUD Functions
	// ============================================================

	// Get all tutors ordered by date joined
	function getAllTutors($pdo) {
		$sql = "SELECT t.*, u1.username AS added_by_username, u2.username AS last_updated_by_username 
				FROM tutors t 
				LEFT JOIN users u1 ON t.added_by = u1.user_id 
				LEFT JOIN users u2 ON t.last_updated_by = u2.user_id 
				ORDER BY t.date_joined DESC, t.tutor_id DESC";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Get a single tutor by ID
	function getTutorByID($pdo, $tutor_id) {
		$sql = "SELECT t.*, u1.username AS added_by_username, u2.username AS last_updated_by_username 
				FROM tutors t 
				LEFT JOIN users u1 ON t.added_by = u1.user_id 
				LEFT JOIN users u2 ON t.last_updated_by = u2.user_id 
				WHERE t.tutor_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$tutor_id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Alias for getTutorByID (used in view/header displays)
	function getAllInfoByTutorID($pdo, $tutor_id) {
		return getTutorByID($pdo, $tutor_id);
	}

	// Insert a new tutor (with added_by tracking)
	function insertTutor($pdo, $firstName, $lastName, $email, $phoneNumber, $specialization, $hourlyRate, $added_by) {
		$sql = "INSERT INTO tutors (first_name, last_name, email, phone_number, specialization, hourly_rate, added_by, last_updated_by) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $specialization, $hourlyRate, $added_by, $added_by]);

		if ($executeQuery) {
			return true;
		}
		return false;
	}

	// Update tutor details (with last_updated and last_updated_by tracking)
	function updateTutor($pdo, $tutor_id, $firstName, $lastName, $email, $phoneNumber, $specialization, $hourlyRate, $last_updated_by) {
		$sql = "UPDATE tutors 
				SET first_name = ?, last_name = ?, email = ?, phone_number = ?, specialization = ?, hourly_rate = ?,
				    last_updated_by = ?, last_updated = CURRENT_TIMESTAMP
				WHERE tutor_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$firstName, $lastName, $email, $phoneNumber, $specialization, $hourlyRate, $last_updated_by, $tutor_id]);

		if ($executeQuery) {
			return true;
		}
		return false;
	}

	// Delete a tutor (cascades to delete their sessions)
	function deleteTutor($pdo, $tutor_id) {
		$sql = "DELETE FROM tutors WHERE tutor_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$tutor_id]);

		if ($executeQuery) {
			return true;
		}
		return false;
	}


	// ============================================================
	// SESSIONS (CHILD TABLE) - CRUD Functions
	// ============================================================

	// Get all sessions for a specific tutor with tutor's full name, added_by and last_updated_by users
	function getSessionsByTutor($pdo, $tutor_id) {
		$sql = "SELECT s.*, CONCAT(t.first_name, ' ', t.last_name) AS tutor_name, 
				       u1.username AS added_by_username, u2.username AS last_updated_by_username 
				FROM sessions s 
				INNER JOIN tutors t ON s.tutor_id = t.tutor_id 
				LEFT JOIN users u1 ON s.added_by = u1.user_id 
				LEFT JOIN users u2 ON s.last_updated_by = u2.user_id 
				WHERE s.tutor_id = ? 
				ORDER BY s.session_date DESC, s.start_time DESC";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$tutor_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	// Get a single session by ID
	function getSessionByID($pdo, $session_id) {
		$sql = "SELECT s.*, CONCAT(t.first_name, ' ', t.last_name) AS tutor_name, 
				       u1.username AS added_by_username, u2.username AS last_updated_by_username 
				FROM sessions s 
				INNER JOIN tutors t ON s.tutor_id = t.tutor_id 
				LEFT JOIN users u1 ON s.added_by = u1.user_id 
				LEFT JOIN users u2 ON s.last_updated_by = u2.user_id 
				WHERE s.session_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$session_id]);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	// Insert a new tutoring session (with added_by tracking)
	function insertSession($pdo, $tutor_id, $sessionDate, $startTime, $endTime, $subjectTaught, $notes, $added_by) {
		$sql = "INSERT INTO sessions (tutor_id, session_date, start_time, end_time, subject_taught, notes, added_by, last_updated_by) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$tutor_id, $sessionDate, $startTime, $endTime, $subjectTaught, $notes, $added_by, $added_by]);

		if ($executeQuery) {
			return true;
		}
		return false;
	}

	// Update an existing tutoring session (with last_updated and last_updated_by tracking)
	function updateSession($pdo, $session_id, $sessionDate, $startTime, $endTime, $subjectTaught, $notes, $last_updated_by) {
		$sql = "UPDATE sessions 
				SET session_date = ?, start_time = ?, end_time = ?, subject_taught = ?, notes = ?,
				    last_updated_by = ?, last_updated = CURRENT_TIMESTAMP
				WHERE session_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$sessionDate, $startTime, $endTime, $subjectTaught, $notes, $last_updated_by, $session_id]);

		if ($executeQuery) {
			return true;
		}
		return false;
	}

	// Delete a session
	function deleteSession($pdo, $session_id) {
		$sql = "DELETE FROM sessions WHERE session_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$session_id]);

		if ($executeQuery) {
			return true;
		}
		return false;
	}

?>
