<?php
include "../db.php";
 
// Only allow librarian
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: ../index.php");
    exit();
}

$message = "";

/* =========================
   GET LIBRARIAN SCHOOL ID
   users.school_id = school_code (TEXT)
   books.school_id = schools.id (INT)
========================= */

$librarian_email = $_SESSION['email'];

// Step 1: get school_code from users
$stmt = $conn->prepare("SELECT school_id FROM users WHERE email = ?");
$stmt->bind_param("s", $librarian_email);
$stmt->execute();
$result = $stmt->get_result();
$librarian = $result->fetch_assoc();

if (!$librarian || empty($librarian['school_id'])) {
    die("Error: Librarian is not assigned to any school.");
}

$school_code = $librarian['school_id'];

// Step 2: convert school_code → schools.id
$stmt = $conn->prepare("SELECT id FROM schools WHERE school_code = ?");
$stmt->bind_param("s", $school_code);
$stmt->execute();
$result = $stmt->get_result();
$school = $result->fetch_assoc();

if (!$school) {
    die("Error: School not found.");
}

$school_id = (int)$school['id'];

/* =========================
   HANDLE FORM SUBMISSION
========================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);

    if (isset($_FILES['book_file']) && $_FILES['book_file']['error'] === UPLOAD_ERR_OK) {

        $fileTmp = $_FILES['book_file']['tmp_name'];
        $fileName = basename($_FILES['book_file']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExt !== "pdf") {
            $message = "Only PDF files are allowed.";
        } else {

            $uploadDir = __DIR__ . "/../uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = uniqid("book_") . ".pdf";
            $fullUploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $fullUploadPath)) {

                $stmt = $conn->prepare("
                    INSERT INTO books (title, author, category, file_path, school_id)
                    VALUES (?, ?, ?, ?, ?)
                ");

                // IMPORTANT: last type is integer
                $stmt->bind_param("ssssi", $title, $author, $category, $newFileName, $school_id);

                if ($stmt->execute()) {
                    $message = "Book uploaded successfully.";
                } else {
                    $message = "Database error: " . $stmt->error;
                }

            } else {
                $message = "Failed to upload the file.";
            }
        }

    } else {
        $message = "Please select a file to upload.";
    }
}
?>

<div class="min-h-screen bg-gray-900 text-white p-8">

<h2 class="text-2xl font-semibold mb-6">Upload New Book</h2>

<?php if ($message !== ""): ?>
    <p class="text-green-400 mb-4"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="space-y-4 max-w-lg">

    <input name="title" placeholder="Book Title" required
        class="w-full p-3 rounded bg-gray-800 border border-gray-700">

    <input name="author" placeholder="Author" required
        class="w-full p-3 rounded bg-gray-800 border border-gray-700">

    <input name="category" placeholder="Category" required
        class="w-full p-3 rounded bg-gray-800 border border-gray-700">

    <input type="file" name="book_file" accept="application/pdf" required
        class="w-full p-3 rounded bg-gray-800 border border-gray-700">

    <button class="bg-green-600 px-5 py-2 rounded hover:bg-green-700 transition">
        Upload Book
    </button>

</form>

</div>
