<?php

include "../db.php";
$email = $_SESSION['email'];
$message = "";
$error = "";

/* =========================
   GET CURRENT USER
========================= */

$stmt = $conn->prepare("SELECT id, full_name, password, school_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$user_id = $user['id'];
$currentName = $user['full_name'];
$currentPasswordHash = $user['password'];
$schoolCode = $user['school_id'];

/* =========================
   UPDATE PROFILE
========================= */

if (isset($_POST['update_profile'])) {
    $newName = trim($_POST['full_name']);

    if ($newName === "") {
        $error = "Full name cannot be empty.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET full_name = ? WHERE id = ?");
        $stmt->bind_param("si", $newName, $user_id);
        $stmt->execute();

        $message = "Profile updated successfully.";
        $currentName = $newName;
    }
}

/* =========================
   CHANGE PASSWORD
========================= */

if (isset($_POST['change_password'])) {

    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (!password_verify($oldPassword, $currentPasswordHash)) {
        $error = "Current password is incorrect.";
    }
    elseif ($newPassword !== $confirmPassword) {
        $error = "New passwords do not match.";
    }
    elseif (strlen($newPassword) < 6) {
        $error = "Password must be at least 6 characters.";
    }
    else {
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $newHash, $user_id);
        $stmt->execute();

        $message = "Password changed successfully.";
    }
}
?>

<h2 class="text-2xl font-semibold mb-6">Settings</h2>

<?php if ($message): ?>
<div class="bg-green-600 p-3 rounded mb-4"><?= $message ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="bg-red-600 p-3 rounded mb-4"><?= $error ?></div>
<?php endif; ?>

<div class="grid md:grid-cols-2 gap-6">

<!-- PROFILE SETTINGS -->
<div class="bg-gray-800 p-6 rounded-lg">
<h3 class="text-lg font-semibold mb-4">Profile Settings</h3>

<form method="POST" class="space-y-4">

<div>
<label class="text-sm text-gray-400">Full Name</label>
<input name="full_name" value="<?= htmlspecialchars($currentName) ?>"
class="w-full p-3 rounded bg-gray-900 border border-gray-700">
</div>

<div>
<label class="text-sm text-gray-400">School Code</label>
<input value="<?= htmlspecialchars($schoolCode) ?>" readonly
class="w-full p-3 rounded bg-gray-900 border border-gray-700 opacity-70">
</div>

<button name="update_profile"
class="bg-blue-600 px-5 py-2 rounded hover:bg-blue-700">
Update Profile
</button>

</form>
</div>

<!-- PASSWORD SETTINGS -->
<div class="bg-gray-800 p-6 rounded-lg">
<h3 class="text-lg font-semibold mb-4">Change Password</h3>

<form method="POST" class="space-y-4">

<input type="password" name="old_password" placeholder="Current Password"
class="w-full p-3 rounded bg-gray-900 border border-gray-700" required>

<input type="password" name="new_password" placeholder="New Password"
class="w-full p-3 rounded bg-gray-900 border border-gray-700" required>

<input type="password" name="confirm_password" placeholder="Confirm New Password"
class="w-full p-3 rounded bg-gray-900 border border-gray-700" required>

<button name="change_password"
class="bg-green-600 px-5 py-2 rounded hover:bg-green-700">
Change Password
</button>

</form>
</div>

</div>
