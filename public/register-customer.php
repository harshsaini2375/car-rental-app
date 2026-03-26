<?php 

include '../config.php';

$success = false;
$errors = [];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // getting data from form
    $full_name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';


    // checking id data entered is valid or not
    if (empty($full_name)) {
        $errors[] = "Full name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    } 
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // If no errors, proceed to database insertion
    if (empty($errors)) {
        // we always store hashed password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to prevent data from hackers or we can simply insert data
        $sql = "INSERT INTO `customers` (full_name, email, phone, address, password_hash) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        // inserting data
        mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $phone, $address, $hashed_password);

        if (mysqli_stmt_execute($stmt)) {
            $success = true;
            // Optionally redirect after success:
            // header("Location: login.php?registered=1");
            // exit;
            
        } else {           
                $errors[] = "Database error: " . mysqli_error($conn);   
        }
        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration | Dream Drive</title>
    <link rel="stylesheet" href="/car/style.css">
</head>
<body>
    
 <?php include('../reuse/navbar.php'); ?>

    <main class="auth-page">
        <div class="container auth-container">
            <div class="auth-card">
                <h2>Create Customer Account</h2>
                <p>Join Dream Drive to rent luxury cars</p>


                 <?php if ($success): ?>
                    <div class="success-message">
                        Registration successful! You can now <a href="login.php">log in</a>.
                    </div>
                <?php elseif (!empty($errors)): ?>
                    <div class="error-message">
                        <?php foreach ($errors as $err): ?>
                            <p><?php echo htmlspecialchars($err); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


                <form action="" method="post" id="customerForm">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address (Optional)</label>
                        <textarea id="address" name="address" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-dark">Register</button>
                </form>
                <p class="auth-footer">Already have an account? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </main>

    <?php include('../reuse/footer.php'); ?>
  

   
</body>
</html>