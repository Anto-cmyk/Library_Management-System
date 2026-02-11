<?php
session_start();

/* if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "user") {
    header("Location: login.php");
    exit();
} */

$page = $_GET['page'] ?? 'home';

$allowed_pages = ['home', 'profile', 'books', 'settings'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

<div class="flex min-h-screen">

<?php include "sidebar.php"; ?>

<div class="flex-1">

<?php include "header.php"; ?>

<div class="p-6">
<?php include "$page.php"; ?>
</div>

 
</div>
</div>

</body>
</html>
