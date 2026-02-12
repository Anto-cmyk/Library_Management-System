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

$school_id = $school['id'];

$stmt = $conn->prepare("SELECT * FROM books WHERE school_id = ?");
$stmt->bind_param("i", $school_id);
$stmt->execute();
$books = $stmt->get_result();
?>

<h2 class="text-2xl font-semibold mb-6">Browse Books</h2>

<?php if ($books->num_rows == 0): ?>
<p class="text-gray-400">No books available.</p>
<?php else: ?>

<div class="grid grid-cols-3 gap-4">
<?php while($book = $books->fetch_assoc()): ?>
<div class="bg-gray-800 p-4 rounded-lg">
<p class="font-semibold"><?= htmlspecialchars($book['title']) ?></p>
<p class="text-gray-400 text-sm"><?= htmlspecialchars($book['author']) ?></p>

<a href="../uploads/<?= $book['file_path'] ?>" target="_blank"
class="inline-block mt-3 bg-blue-600 px-3 py-1 rounded">
Read Book
</a>
</div>
<?php endwhile; ?>
</div>

<?php endif; ?>
