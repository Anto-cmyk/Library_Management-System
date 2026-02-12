<?php
 session_start();

 include "db.php"; 

 $message =  '';

 if($_SERVER['REQUEST_METHOD'] === 'POST') 
   { $email = $_POST['email']; 
   $password = $_POST['password'];
   
   $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); $stmt->execute(); 
    $result = $stmt->get_result(); 
    
    if ($result->num_rows === 1) 
      { $user = $result->fetch_assoc();
      
       if (password_verify($password, $user['password'])) 
         { $_SESSION['user_id'] = $user['id']; 
          $_SESSION['full_name'] = $user['full_name']; 
          $_SESSION['role'] = $user['role']; 
          $_SESSION['email'] = $email;
          
           if ($user['role'] === 'librarian') 
             { header("Location: Librarian/routes.php"); exit(); }
             
              else { header("Location: Users/routes.php"); exit(); } } 
               else { $message = "Invalid email or password"; } } 
               else { $message =  "Invalid email or password"; } } ?>
 
 
 



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white flex items-center justify-center">

<div class="w-full max-w-md">

<div class="bg-white/10 backdrop-blur-lg border border-white/20 shadow-2xl rounded-2xl p-8">

<h2 class="text-3xl font-semibold text-center mb-1">Welcome Back</h2>
<p class="text-gray-300 text-center mb-6 text-sm">Login to continue</p>

<!-- PHP Alert Example -->
<!--
<div class="bg-red-500/20 border border-red-400 text-red-300 text-sm p-3 rounded-lg mb-4">
Invalid email or password
</div>
-->

<form action="index.php" method="POST" class="space-y-4">

<?php echo " <p class='block text-red-700'>$message</p>"; ?>

<input type="email" name="email" placeholder="Email"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none"
required>

<div class="relative">
<input type="password" id="password" name="password" placeholder="Password"
class="w-full p-3 pr-12 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none"
required>

<button type="button" onclick="togglePassword()"
class="absolute right-3 top-3 text-gray-400 hover:text-white text-sm">
Show
</button>
</div>

<div class="flex items-center justify-between text-sm text-gray-300">
<label class="flex items-center gap-2">
<input type="checkbox" name="remember" class="accent-blue-600">
Remember me
</label>

<button type="button" onclick="openForgot()" class="text-blue-400 hover:underline">
Forgot password?
</button>
</div>

<button class="w-full bg-blue-600 hover:bg-blue-700 transition p-3 rounded-lg font-semibold">
 <a href="Users/routes.php" >LOGIN</a>
</button>

</form>
</div>

<p class="text-center text-gray-400 text-sm mt-4">
No account? <a href="register.php" class="text-blue-400 hover:underline">Register</a>
</p>

</div>

<!-- FORGOT PASSWORD MODAL -->
<div id="forgotModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center">

<div class="bg-gray-900 border border-gray-700 rounded-xl p-6 w-full max-w-sm">

<h3 class="text-xl font-semibold mb-2">Reset Password</h3>
<p class="text-gray-400 text-sm mb-4">Enter your email to reset password</p>

<form action="forgot_password.php" method="POST" class="space-y-3">

<input type="email" name="email" placeholder="Enter your email"
class="w-full p-3 rounded-lg bg-gray-800 border border-gray-600 focus:border-blue-500 focus:outline-none"
required>

<button class="w-full bg-blue-600 hover:bg-blue-700 p-3 rounded-lg font-semibold">
Send Reset Link
</button>

<button type="button" onclick="closeForgot()"
class="w-full text-gray-400 hover:text-white text-sm">
Cancel
</button>

</form>

</div>
</div>

<script>
function togglePassword(){
    let input = document.getElementById("password");
    let btn = event.target;

    if(input.type === "password"){
        input.type = "text";
        btn.textContent = "Hide";
    } else {
        input.type = "password";
        btn.textContent = "Show";
    }
}

function openForgot(){
    document.getElementById("forgotModal").classList.remove("hidden");
    document.getElementById("forgotModal").classList.add("flex");
}

function closeForgot(){
    document.getElementById("forgotModal").classList.add("hidden");
}
</script>

</body>
</html>
