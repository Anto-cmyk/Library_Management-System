<?php
include "../db.php";
 
/* =========================
   AUTH CHECK
========================= */
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
   MOST AVAILABLE BOOKS
========================= */
$stmt = $conn->prepare("
    SELECT title, author, category, created_at
    FROM books
    WHERE school_id = ?
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->bind_param("i", $school_id);
$stmt->execute();
$books = $stmt->get_result();

/* =========================
   ACTIVE USERS
========================= */
$stmt = $conn->prepare("
    SELECT full_name, email, created_at
    FROM users
    WHERE school_id = ? AND role = 'user'
    ORDER BY created_at DESC
    LIMIT 5
");
$stmt->bind_param("s", $school_code);
$stmt->execute();
$users = $stmt->get_result();

/* =========================
   MONTHLY BOOK UPLOADS
========================= */
$stmt = $conn->prepare("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total
    FROM books
    WHERE school_id = ?
    GROUP BY month
    ORDER BY month DESC
    LIMIT 6
");
$stmt->bind_param("i", $school_id);
$stmt->execute();
$monthly = $stmt->get_result();
?>

<div class="min-h-screen bg-gray-900 text-white p-8">

<h2 class="text-2xl font-semibold mb-6">Library Reports</h2>
<p class="text-gray-400 mb-8">
School: <span class="text-white"><?= htmlspecialchars($school_name) ?></span>
</p>

<div class="space-y-6">

<!-- MOST AVAILABLE BOOKS -->
<div class="bg-gray-800 p-5 rounded-lg">
<p class="text-lg font-semibold mb-3">Recent Books</p>

<?php if ($books->num_rows > 0): ?>
    <ul class="space-y-2 text-gray-300">
        <?php while($b = $books->fetch_assoc()): ?>
            <li>
                <?= htmlspecialchars($b['title']) ?>
                <span class="text-gray-500 text-sm">by <?= htmlspecialchars($b['author']) ?></span>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p class="text-gray-400">No books uploaded yet.</p>
<?php endif; ?>
</div>

<!-- ACTIVE USERS -->
<div class="bg-gray-800 p-5 rounded-lg">
<p class="text-lg font-semibold mb-3">Active Users</p>

<?php if ($users->num_rows > 0): ?>
    <ul class="space-y-2 text-gray-300">
        <?php while($u = $users->fetch_assoc()): ?>
            <li><?= htmlspecialchars($u['full_name']) ?></li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p class="text-gray-400">No users registered.</p>
<?php endif; ?>
</div>

<!-- MONTHLY ACTIVITY -->
<div class="bg-gray-800 p-5 rounded-lg">
<p class="text-lg font-semibold mb-3">Monthly Book Uploads</p>

<?php if ($monthly->num_rows > 0): ?>
    <ul class="space-y-2 text-gray-300">
        <?php while($m = $monthly->fetch_assoc()): ?>
            <li><?= $m['month'] ?> → <?= $m['total'] ?> books</li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p class="text-gray-400">No activity yet.</p>
<?php endif; ?>
</div>

</div>
</div>
