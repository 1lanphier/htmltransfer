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
    <title>Google</title>
    <link rel="icon" type="image/x-icon" href="https://www.google.com/favicon.ico">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>
        .text-padding {
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .type-div {
            display: grid;
            font-family: "Courier New";
            place-items: center;
            width: 195px;
            font-size: 24px;
            color: white;
        }

        .typed {
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid white;
            width: 0;
            animation: typing 1.5s steps(15, end) forwards, blinking 1s infinite;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blinking {
            0% {border-right-color: transparent}
            50% {border-right-color: white}
            100%{border-right-color: transparent}
        }

        .container-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }
    </style>
</head>
<body>
    <div class="bg-dark" style="height: 100vh">
        <div class="container"> 
            <header>
                <div>
                    <img src="tenaxglitch.png" class="mx-auto d-block" style="max-width: 40%; height: auto; padding-top: 25px;"/>
                </div>
                <div class="type-div mx-auto d-block">
                    <p class="typed">SH0W N0 M3RCY</p>
                </div>
            </header>
            <div class="container bg-light rounded" style="height: auto; padding: 20px;">    
                <h2 class="text-padding text-center" style="font-family: Courier New">HTTP FILE EXFILTRATOR</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <!-- File Upload -->
                                <div class="input-group mb-3">
                                    <input type="file" name="files[]" id="file_input" class="form-control" multiple>
                                </div>

                                <!-- Folder Upload -->
                                <div class="input-group mb-3">
                                    <input type="file" id="folder_input" name="folder_files[]" class="form-control" multiple webkitdirectory directory>
                                </div>

                                <!-- Hidden input to store folder paths -->
                                <input type="hidden" id="folder_paths" name="folder_paths">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-secondary">Upload Files & Folders</button>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>   
                </form>
            </div>
        </div> 
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
