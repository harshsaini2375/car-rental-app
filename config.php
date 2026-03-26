<!-- here we are connecting to our database -->

<?php
// Database configuration
$host = 'localhost';        
$user = 'root';             
$password = '';             
$database = 'dream_drive';  

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// echo "Connected successfully";
?>

<!-- INSERT INTO `customers` (`customer_id`, `full_name`, `email`, `password_hash`, `phone`, `address`, `created_at`, `updated_at`) VALUES (NULL, 'harsh', 'harsh@email.com', '12345', '999999999', 'street', current_timestamp(), current_timestamp()); -->