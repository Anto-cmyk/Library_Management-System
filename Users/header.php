<div class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center">

<h2 class="text-lg font-semibold capitalize">
<?php echo $_GET['page'] ?? 'home'; ?>
</h2>

<div class="text-sm text-gray-300">
Welcome, <?php echo $_SESSION['full_name']; ?>
</div>

</div>
