<?php
include "../db.php";

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT full_name, email, school_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<h2 class="text-2xl font-semibold mb-6">Profile</h2>

<div class="bg-gray-800 p-5 rounded-lg space-y-2">
<p><strong>Name:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
<p><strong>School Code:</strong> <?= htmlspecialchars($user['school_id']) ?></p>
</div>
