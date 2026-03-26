<?php 

include '../config.php';

$success = false;
$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get data 
    $agency_name = trim($_POST['agency_name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $registration_number = trim($_POST['registration_number'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($agency_name)) {
        $errors[] = "Agency name is required.";
    }
    if (empty($contact_person)) {
        $errors[] = "Contact person name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }
    if (empty($registration_number)) {
        $errors[] = "Agency registration number / Tax ID is required.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

   
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement
        $sql = "INSERT INTO agencies (agency_name, contact_person, email, phone, address, registration_number, password_hash) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", 
            $agency_name, 
            $contact_person, 
            $email, 
            $phone, 
            $address, 
            $registration_number, 
            $hashed_password
        );

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
    <title>Agency Registration | Dream Drive</title>
    <link rel="stylesheet" href="/car/style.css">
</head>
<body>

  <?php include('../reuse/navbar.php'); ?>

    <main class="auth-page">
        <div class="container auth-container">
            <div class="auth-card">
                <h2>Register Your Car Agency</h2>
                <p>Partner with Dream Drive to list your fleet</p>


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


                <form action="" method="post" id="agencyForm">
                    <div class="form-group">
                        <label for="agency_name">Agency Name</label>
                        <input type="text" id="agency_name" name="agency_name" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_person">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" required>
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
                        <label for="address">Business Address</label>
                        <textarea id="address" name="address" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="registration_number">Agency Registration Number / Tax ID</label>
                        <input type="text" id="registration_number" name="registration_number" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-dark">Register Agency</button>
                </form>
                <p class="auth-footer">Already registered? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </main>

   <?php include('../reuse/footer.php'); ?>
  
</body>
</html>