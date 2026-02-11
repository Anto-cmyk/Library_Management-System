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

<form action="process_register.php" method="POST" class="space-y-4">

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
