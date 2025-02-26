<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "uploads/";

    // Ensure the uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Function to upload files and maintain folder structure
    function uploadFiles($files, $target_dir, $folder_paths = []) {
        foreach ($files["name"] as $key => $file_name) {
            $file_tmp = $files["tmp_name"][$key];
            $file_error = $files["error"][$key];

            if ($file_error === UPLOAD_ERR_OK) {
                // Preserve folder structure if folder_paths exists
                $sub_folder = isset($folder_paths[$key]) ? trim($folder_paths[$key], "/") . "/" : "";
                $target_folder = $target_dir . $sub_folder;

                // Ensure the sub-folder exists
                if (!is_dir($target_folder)) {
                    mkdir($target_folder, 0755, true);
                }

                $unique_name = uniqid() . "_" . basename($file_name);
                $target_file = $target_folder . $unique_name;

                if (move_uploaded_file($file_tmp, $target_file)) {
                    echo "<div class='alert alert-success'>Success: " . htmlspecialchars($sub_folder . $file_name) . " uploaded.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error: Failed to upload " . htmlspecialchars($file_name) . ".</div>";
                }
            }
        }
    }

    // Upload regular files
    if (isset($_FILES["files"])) {
        uploadFiles($_FILES["files"], $target_dir);
    }

    // Upload folder files (preserve folder structure)
    if (isset($_FILES["folder_files"]) && isset($_POST["folder_paths"])) {
        uploadFiles($_FILES["folder_files"], $target_dir, json_decode($_POST["folder_paths"], true));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Files and Folders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Upload Files and Folders</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <!-- File Upload -->
            <div class="mb-3">
                <label for="file_input" class="form-label">Select Files:</label>
                <input type="file" name="files[]" id="file_input" class="form-control" multiple>
            </div>

            <!-- Folder Upload -->
            <div class="mb-3">
                <label for="folder_input" class="form-label">Select Folder:</label>
                <input type="file" id="folder_input" name="folder_files[]" class="form-control" multiple webkitdirectory directory>
            </div>

            <!-- Hidden input to store folder paths -->
            <input type="hidden" id="folder_paths" name="folder_paths">

            <button type="submit" class="btn btn-primary w-100">Upload Files & Folders</button>
        </form>
    </div>

    <script>
        document.getElementById("folder_input").addEventListener("change", function(event) {
            let folderPaths = [];
            for (let i = 0; i < event.target.files.length; i++) {
                let path = event.target.files[i].webkitRelativePath;
                folderPaths.push(path.substring(0, path.lastIndexOf("/"))); // Store folder structure
            }
            document.getElementById("folder_paths").value = JSON.stringify(folderPaths);
        });
    </script>
</body>
</html>
