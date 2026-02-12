<?php
include "../db.php";
 
// Only librarian
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: ../index.php");
    exit();
}

$message = "";

/* =========================
   GET LIBRARIAN SCHOOL ID
========================= */

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT school_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$school_code = $user['school_id'] ?? null;

$stmt = $conn->prepare("SELECT id FROM schools WHERE school_code = ?");
$stmt->bind_param("s", $school_code);
$stmt->execute();
$result = $stmt->get_result();
$school = $result->fetch_assoc();

if (!$school) {
    die("School not found.");
}

$school_id = $school['id'];

/* =========================
   DELETE BOOK
========================= */

if (isset($_GET['delete'])) {
    $book_id = (int)$_GET['delete'];

    // get file name (only from same school)
    $stmt = $conn->prepare("SELECT file_path FROM books WHERE id = ? AND school_id = ?");
    $stmt->bind_param("ii", $book_id, $school_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book) {
        $filePath = __DIR__ . "/../uploads/" . $book['file_path'];

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $stmt = $conn->prepare("DELETE FROM books WHERE id = ? AND school_id = ?");
        $stmt->bind_param("ii", $book_id, $school_id);
        $stmt->execute();

        $message = "Book deleted successfully.";
    }
}

/* =========================
   FETCH BOOKS
========================= */

$stmt = $conn->prepare("
    SELECT id, title, author, category, file_path, created_at
    FROM books
    WHERE school_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $school_id);
$stmt->execute();
$books = $stmt->get_result();
?>

<div class="min-h-screen bg-gray-900 text-white p-8">

<h2 class="text-2xl font-semibold mb-6">Manage Books</h2>

<?php if ($message): ?>
    <p class="text-green-400 mb-4"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<?php if ($books->num_rows === 0): ?>
    <p class="text-gray-400">No books available.</p>
<?php else: ?>

<div class="overflow-x-auto">
<table class="w-full border border-gray-700">
<thead class="bg-gray-800">
<tr>
<th class="p-3">Title</th>
<th class="p-3">Author</th>
<th class="p-3">Category</th>
<th class="p-3">File</th>
<th class="p-3">Action</th>
</tr>
</thead>

<tbody>
<?php while ($row = $books->fetch_assoc()): ?>
<tr class="border-t border-gray-700">
<td class="p-3"><?= htmlspecialchars($row['title']) ?></td>
<td class="p-3"><?= htmlspecialchars($row['author']) ?></td>
<td class="p-3"><?= htmlspecialchars($row['category']) ?></td>

<td class="p-3">
<a href="../uploads/<?= urlencode($row['file_path']) ?>"
   target="_blank"
   class="text-blue-400 hover:underline">
   View
</a>
</td>

<td class="p-3">
<a href="?delete=<?= $row['id'] ?>"
   onclick="return confirm('Delete this book?')"
   class="bg-red-600 px-3 py-1 rounded hover:bg-red-700">
   Delete
</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

<?php endif; ?>

</div>
