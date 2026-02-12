<?php

include "../db.php";

// Only allow librarian
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: ../index.php");
    exit();
}

// Get librarian's school_id
$librarian_email = $_SESSION['email']; // assuming login sets this
$stmt = $conn->prepare("SELECT school_id FROM users WHERE email = ?");
$stmt->bind_param("s", $librarian_email);
$stmt->execute();
$result = $stmt->get_result();
$librarian = $result->fetch_assoc();
$school_id = $librarian['school_id'];

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND school_id=? AND role='user'");
    $stmt->bind_param("is", $delete_id, $school_id);
    $stmt->execute();

    header("Location: manage_users.php");
    exit();
}

// Fetch all users in this school
$stmt = $conn->prepare("SELECT id, full_name, email FROM users WHERE school_id=? AND role='user'");
$stmt->bind_param("s", $school_id);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

 
<div class="min-h-screen bg-gray-900 text-white p-8">

<h2 class="text-2xl font-semibold mb-6">Manage Users</h2>

<?php if (count($users) === 0): ?>
    <p class="text-gray-400">No users in your school yet.</p>
<?php else: ?>
    <table class="w-full bg-gray-800 rounded-lg overflow-hidden">
    <thead class="bg-gray-700">
    <tr>
        <th class="p-3 text-left">Name</th>
        <th class="p-3 text-left">Email</th>
        <th class="p-3 text-left">Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($users as $user): ?>
    <tr class="border-t border-gray-700">
        <td class="p-3"><?= htmlspecialchars($user['full_name']) ?></td>
        <td class="p-3"><?= htmlspecialchars($user['email']) ?></td>
        <td class="p-3">
            <a href="manage_users.php?delete_id=<?= $user['id'] ?>" 
               onclick="return confirm('Are you sure you want to remove this user?');"
               class="bg-red-600 px-3 py-1 rounded">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
<?php endif; ?>

</div>



