<?php

include "../db.php";

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT school_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$school_code = $user['school_id'];

$stmt = $conn->prepare("SELECT id FROM schools WHERE school_code = ?");
$stmt->bind_param("s", $school_code);
$stmt->execute();
$school = $stmt->get_result()->fetch_assoc();

$school_id = $school['id'] ?? 0;

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM books WHERE school_id = ?");
$stmt->bind_param("i", $school_id);
$stmt->execute();
$totalBooks = $stmt->get_result()->fetch_assoc()['total'];
?>

<h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

<div class="grid grid-cols-3 gap-4">

<div class="bg-gray-800 p-5 rounded-lg">
<p class="text-gray-400">Available Books</p>
<p class="text-3xl font-bold"><?= $totalBooks ?></p>
</div>

</div>
