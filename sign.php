<?php
include_once 'dbConnection.php';
ob_start();

// Function to check if username exists in the database
function checkUsernameExists($username, $con) {
    // Escape the username to prevent SQL injection
    $escapedUsername = mysqli_real_escape_string($con, $username);

    // Query to check if the username exists in the 'user' table
    $query = "SELECT COUNT(*) as count FROM user WHERE username = '$escapedUsername'";
    
    // Execute the query
    $result = mysqli_query($con, $query);

    // Check if the query was successful and fetch the result
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];

        // Return true if username exists, false otherwise
        return $count > 0;
    } else {
        // Error handling (you can customize this based on your needs)
        echo "Error checking username: " . mysqli_error($con);
        return false;
    }
}

// Validate phone number
function validatePhoneNumber($phno) {
    $pattern = '/^\d{10}$/'; // Assumes a 10-digit phone number
    return preg_match($pattern, $phno);
}

// Validate roll number pattern
function validateRollNumber($rollno) {
    $pattern = '/^\d[A-Za-z]{2}\d{2}[A-Za-z]{2}\d{3}$/'; // Pattern for roll number validation
    return preg_match($pattern, $rollno);
}

// Validate password strength
function validatePasswordStrength($password) {
    // Define criteria for a strong password (e.g., minimum length, uppercase, lowercase, numbers, special characters)
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password); // Assuming special characters are anything that's not alphanumeric or underscore
    
    // Check if the password meets the criteria
    return $uppercase && $lowercase && $number && $specialChars && strlen($password) >= 8; // Example criteria: at least 8 characters with uppercase, lowercase, number, and special character
}

$name     = $_POST['name'];
$name     = ucwords(strtolower($name));
$gender   = $_POST['gender'];
$username = $_POST['username'];
$phno     = $_POST['phno'];
$password = $_POST['password'];
$cpassword = $_POST['cpassword'];
$branch   = $_POST['branch'];
$rollno   = $_POST['rollno'];
$name     = stripslashes($name);
$name     = addslashes($name);
$name     = ucwords(strtolower($name));
$gender   = stripslashes($gender);
$gender   = addslashes($gender);
$username = stripslashes($username);
$username = addslashes($username);
$phno     = stripslashes($phno);
$phno     = addslashes($phno);
$password = stripslashes($password);
$password = addslashes($password);
$password = md5($password);
$cpassword = stripslashes($cpassword);
$cpassword = addslashes($cpassword);
$cpassword = md5($cpassword);

// Check if password and confirm password match
if ($password !== $cpassword) {
    header("location:index.php?q27=Passwords do not match.");
    exit(); // Exit to prevent further execution
}
if (validatePhoneNumber($phno)) {
    if (!validateRollNumber($rollno)) {
        header("location:index.php?q17=Invalid roll number pattern.");
        exit();
         // Exit to prevent further execution
    }
    // Check if username exists
    if (checkUsernameExists($username, $con)) {
        header("location:index.php?q77=Username already exists.");
        
    } 
    if (!validatePasswordStrength($_POST['password'])) {
        header("location:index.php?q07=Weak password. Passwords must be at least 8 characters long and include uppercase, lowercase, number, and special character.");
        exit(); // Exit to prevent further execution
    }

    else {
        // Insert user data into the database
        $q3 = mysqli_query($con, "INSERT INTO user VALUES  (NULL,'$name', '$rollno','$branch','$gender' ,'$username' ,'$phno', '$password')");
        if ($q3) {
            session_start();
            $_SESSION["username"] = $username;
            $_SESSION["name"]     = $name;
            header("location:account.php?q=1");
        } else {
            header("location:index.php?q7=Error inserting data.");
        }
    }
} else {
    header("location:index.php?q7=Invalid phone number.");
}
ob_end_flush();
?>
