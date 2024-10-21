<?php
// Function to list uploaded files
function listUploadedFiles($uploadDirectory) {
    $files = [];
    if (is_dir($uploadDirectory)) {
        if ($handle = opendir($uploadDirectory)) {
            while (($file = readdir($handle)) !== false) {
                if ($file != "." && $file != "..") {
                    $files[] = $file;
                }
            }
            closedir($handle);
        }
    }
    return $files;
}

// Function to delete a file
function deleteFile($fileName, $uploadDirectory) {
    $filePath = $uploadDirectory . '/' . $fileName;
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete']) && isset($_POST['filename'])) {
    $fileName = $_POST['filename'];
    $uploadDirectory = 'uploads';
    
    if (deleteFile($fileName, $uploadDirectory)) {
        echo "File '$fileName' deleted successfully.";
    } else {
        echo "Failed to delete file '$fileName'.";
    }
}

// List uploaded files
$uploadDirectory = 'uploads';
$files = listUploadedFiles($uploadDirectory);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Uploaded Files</title>
</head>
<body>
    <h2>Uploaded Files</h2>
    <?php if (count($files) > 0): ?>
        <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <?php echo $file; ?>
                    [<a href="<?php echo $uploadDirectory . '/' . urlencode($file); ?>" target="_blank">View</a> |
                    <a href="<?php echo $uploadDirectory . '/' . urlencode($file); ?>" download>Download</a> |
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="filename" value="<?php echo $file; ?>">
                        <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this file?')">
                    </form>]
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No files uploaded yet.</p>
    <?php endif; ?>
</body>
</html>
