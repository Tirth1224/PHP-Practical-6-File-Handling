<?php
// Function to validate full name
function validateFullName($name) {
    return preg_match("/^[a-zA-Z ]*$/", $name);
}

// Function to validate mobile number
function validateMobile($mobile) {
    return preg_match("/^\d{10}$/", $mobile);
}

// Function to validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate profile photo (check if uploaded file is an image)
function validateProfilePhoto($file) {
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    return in_array($fileExtension, $allowedExtensions) && getimagesize($file['tmp_name']);
}

// Function to save form data to CSV file
function saveToCSV($data) {
    $file = fopen("students.csv", "a");
    fputcsv($file, $data);
    fclose($file);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $errors = [];
    
    $enrollmentNo = $_POST['enrollmentNo'];
    $fullName = $_POST['fullName'];
    $gender = $_POST['gender'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $profilePhoto = $_FILES['profilePhoto'];

    if (!validateFullName($fullName)) {
        $errors[] = "Invalid full name";
    }

    if (!validateMobile($mobile)) {
        $errors[] = "Invalid mobile number";
    }

    if (!validateEmail($email)) {
        $errors[] = "Invalid email address";
    }

    if (!validateProfilePhoto($profilePhoto)) {
        $errors[] = "Invalid profile photo. Please upload a valid image file (jpg, jpeg, png, gif)";
    }

    // If no errors, save data to CSV file
    if (empty($errors)) {
        $data = array($enrollmentNo, $fullName, $gender, $mobile, $email, $address);
        saveToCSV($data);
        echo "Data saved successfully!";
    } else {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
</head>
<body>
    <h2>Student Registration Form</h2>
    <form method="post" enctype="multipart/form-data">
        Enrollment No.: <input type="text" name="enrollmentNo" required><br><br>
        Full Name: <input type="text" name="fullName" required><br><br>
        Gender: <input type="radio" name="gender" value="male" checked> Male
                <input type="radio" name="gender" value="female"> Female
                <input type="radio" name="gender" value="other"> Other<br><br>
        Mobile: <input type="text" name="mobile" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Address: <textarea name="address" rows="4" cols="30"></textarea><br><br>
        Profile Photo: <input type="file" name="profilePhoto" accept="image/*" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
