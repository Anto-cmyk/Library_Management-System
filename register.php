<?php
session_start();
include "db.php";

$schoolName = $_POST['school_name'] ?? '';

function generateSchoolCode($schoolName, $conn)
{
    // create short name from school
    $words = explode(" ", strtoupper($schoolName));
    $short = "";

    foreach ($words as $w) {
        $short .= substr($w, 0, 1);
    }

    $short = substr($short, 0, 4); // limit length

    do {
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $code = $short . "-" . $random;

        $stmt = $conn->prepare("SELECT id FROM schools WHERE school_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();

    } while ($result->num_rows > 0);

    return $code;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $role = $_POST['role'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match";
    } else {

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // =========================
        // LIBRARIAN REGISTRATION
        // =========================
        if ($role === 'librarian') {

            $school_name = $_POST['school_name'];

            // insert librarian first
            $stmt = $conn->prepare("INSERT INTO users (email, full_name, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $email, $full_name, $password_hash, $role);
            $stmt->execute();

            $librarian_id = $stmt->insert_id;

            // generate school code
            $school_code = generateSchoolCode($school_name, $conn);

            // insert school
            $stmt2 = $conn->prepare("INSERT INTO schools (school_name, school_code, librarian_id) VALUES (?, ?, ?)");
            $stmt2->bind_param("ssi", $school_name, $school_code, $librarian_id);
            $stmt2->execute();

            // update librarian with school id
            $stmt3 = $conn->prepare("UPDATE users SET school_id=? WHERE id=?");
            $stmt3->bind_param("si", $school_code, $librarian_id);
            $stmt3->execute();

            header("Location: index.php?message=School created. School ID: $school_code");
            exit();
        }

        // =========================
        // USER REGISTRATION
        // =========================
        if ($role === 'user') {

            $school_code = $_POST['school_id'];

            // verify school exists
            $stmt = $conn->prepare("SELECT id FROM schools WHERE school_code=?");
            $stmt->bind_param("s", $school_code);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $message = "Invalid School ID";
            } else {

                $stmt = $conn->prepare("
                    INSERT INTO users (email, full_name, password, role, school_id)
                    VALUES (?, ?, ?, ?, ?)
                ");

                $stmt->bind_param("sssss", $email, $full_name, $password_hash, $role, $school_code);
                $stmt->execute();

                header("Location: index.php?message=Account created successfully");
                exit();
            }
        }
    }
}

  ?>





<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Account</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white flex items-center justify-center">

<div class="w-full max-w-md">

<!-- Card -->
<div class="bg-white/10 backdrop-blur-lg border border-white/20 shadow-2xl rounded-2xl p-8">

<h2 class="text-3xl font-semibold text-center mb-1">Create Account</h2>
<p class="text-gray-300 text-center mb-6 text-sm">Join the library system</p>

<form action="register.php" method="POST" class="space-y-4">

<?php echo "<p class='block text-red-700'>$message</p>"; ?>

<!-- Role -->
<div>
<label class="block text-sm text-gray-300 mb-1">Register As</label>
<select name="role" id="role" onchange="toggleForm()"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none">
    <option value="user">User</option>
    <option value="librarian">Librarian</option>
</select>
</div>

<!-- Email -->
<div>
<label class="block text-sm text-gray-300 mb-1">Email</label>
<input type="email" name="email" placeholder="example@email.com"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none"
required>
</div>

<!-- Full Name -->
<div>
<label class="block text-sm text-gray-300 mb-1">Full Name</label>
<input type="text" name="full_name" placeholder="Enter full name"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none"
required>
</div>

<!-- Librarian Fields -->
<div id="librarianFields" class="hidden">
<label class="block text-sm text-gray-300 mb-1">School Full Name</label>
<input type="text" name="school_name" placeholder="Enter school name"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none">
</div>

<!-- User Fields -->
<div id="userFields">
<label class="block text-sm text-gray-300 mb-1">School ID</label>
<input type="text" name="school_id" placeholder="Enter school ID"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none">
</div>

<!-- Password -->
<div>
<label class="block text-sm text-gray-300 mb-1">Password</label>
<input type="password" name="password" placeholder="Enter password"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none"
required>
</div>

<div>
<label class="block text-sm text-gray-300 mb-1">Confirm Password</label>
<input type="password" name="confirm_password"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600"
required>
</div>


<button class="w-full bg-blue-600 hover:bg-blue-700 transition duration-200 p-3 rounded-lg font-semibold shadow-lg">
Create Account
</button>

</form>
</div>

<p class="text-center text-gray-400 text-sm mt-4">
Already have an account? <a href="index.php" class="text-blue-400 hover:underline">Login</a>

</div>

<script>
function toggleForm(){
    let role = document.getElementById("role").value;

    document.getElementById("userFields").style.display =
        role === "user" ? "block" : "none";

    document.getElementById("librarianFields").style.display =
        role === "librarian" ? "block" : "none";
}
</script>

</body>
</html>
