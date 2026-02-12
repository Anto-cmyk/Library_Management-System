<?php
session_start();

 if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'librarian') {
    header("Location: ../index.php");
    exit();
}

$page = $_GET['page'] ?? 'librarian_dashboard';

// Allowed librarian pages
$allowed_pages = [
    'dashboard',
    'manage_books',
    'add_book',
    'manage_users',
    'downloads',
    'report'
];


if (!in_array($page, $allowed_pages)) {
    $page = 'dashboard';
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Librarian Panel</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white flex">

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

<!-- Main Content -->
<div class="flex-1 p-6">

<?php include "$page.php"; ?>

</div>

</body>
</html>
