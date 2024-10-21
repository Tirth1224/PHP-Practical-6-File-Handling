<?php
// Function to generate a unique filename
function generateUniqueFileName($originalName, $uploadDirectory) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    $basename = basename($originalName, '.' . $extension);
    $basename = preg_replace('/[^a-zA-Z0-9]/', '_', $basename);
    $basename = preg_replace('/_{2,}/', '_', $basename);
    $basename = trim($basename, "_");
    $uniqueName = $basename . '_' . uniqid() . '.' . $extension;
    $filePath = $uploadDirectory . '/' . $uniqueName;
    return $filePath;
}

// Function to validate file type and size
function validateFile($file) {
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileSize = $file['size'];
    
    if (!in_array($fileExtension, $allowedTypes)) {
        return "Invalid file type. Allowed types: jpg, jpeg, png, gif";
    }
    
    if ($fileSize > $maxFileSize) {
        return "File size exceeds the maximum limit of 5MB";
    }
    
    return true;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDirectory = 'uploads';
        $filePath = generateUniqueFileName($_FILES['file']['name'], $uploadDirectory);
        
        $validationResult = validateFile($_FILES['file']);
        if ($validationResult === true) {
            if (!file_exists($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                echo "File uploaded successfully.";
            } else {
                echo "Error occurred while uploading the file.";
            }
        } else {
            echo $validationResult;
        }
    } else {
        echo "Error occurred during file upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
</head>
<body>
    <h2>Upload a File</h2>
    <form method="post" enctype="multipart/form-data">
        Select file: <input type="file" name="file" required><br><br>
        <input type="submit" value="Upload">
    </form>
</body>
</html>
