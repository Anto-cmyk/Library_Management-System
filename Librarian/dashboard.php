<?php
include "../db.php";
 
// Only librarian
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: ../index.php");
    exit();
}

/* =========================
   GET LIBRARIAN SCHOOL
========================= */

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT school_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$school_code = $user['school_id'] ?? null;

$stmt = $conn->prepare("SELECT id, school_name FROM schools WHERE school_code = ?");
$stmt->bind_param("s", $school_code);
$stmt->execute();
$result = $stmt->get_result();
$school = $result->fetch_assoc();

if (!$school) {
    die("School not found.");
}

$school_id = $school['id'];
$school_name = $school['school_name'];

/* =========================
   TOTAL BOOKS
========================= */

$stmt = $conn->prepare("SELECT COUNT(*) AS total_books FROM books WHERE school_id = ?");
$stmt->bind_param("i", $school_id);
$stmt->execute();
$totalBooks = $stmt->get_result()->fetch_assoc()['total_books'];

/* =========================
   TOTAL USERS IN SCHOOL
========================= */

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_users
    FROM users
    WHERE school_id = ? AND role = 'user'
");
$stmt->bind_param("s", $school_code);
$stmt->execute();
$totalUsers = $stmt->get_result()->fetch_assoc()['total_users'];
?>

<div class="min-h-screen bg-gray-900 text-white p-8">

<h2 class="text-2xl font-semibold mb-6">
Online Library Dashboard
</h2>

<p class="text-gray-400 mb-8">
School: <span class="text-white font-semibold"><?= htmlspecialchars($school_name) ?></span>
</p>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

<!-- TOTAL BOOKS -->
<div class="bg-gray-800 p-6 rounded-xl shadow">
<p class="text-gray-400">Total Books</p>
<p class="text-3xl font-bold"><?= $totalBooks ?></p>
</div>

<!-- TOTAL USERS -->
<div class="bg-gray-800 p-6 rounded-xl shadow">
<p class="text-gray-400">Registered Users</p>
<p class="text-3xl font-bold"><?= $totalUsers ?></p>
</div>

</div>

</div>
