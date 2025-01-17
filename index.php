<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
    
    // File details
    $file_name = $_FILES["file"]["name"];
    $file_tmp = $_FILES["file"]["tmp_name"];
    $file_size = $_FILES["file"]["size"];
    $file_error = $_FILES["file"]["error"];
    
    // Target directory
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file_name);
    
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<div class='alert alert-warning' style='margin-bottom: -5px'>Warning: File already exists. </div>";
    } else {
        // Check file size (optional)
        // if ($file_size > 500000) {
        //     echo "Sorry, your file is too large.";
        // } else {
            // Upload file
            if (move_uploaded_file($file_tmp, $target_file)) {
                echo "<div class='alert alert-success' style='margin-bottom: -5px'> Success: ". htmlspecialchars(basename($file_name)). " has been uploaded. </div>";
            } else {
                echo "<div class='alert alert-danger' style='margin-bottom: -5px'> Error: An error occured while uploading the file. </div>";
            }
        // }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <title>Google</title>
    <link rel="icon" type="image/x-icon" href="https://www.google.com/favicon.ico">
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
            <div class="container bg-light rounded" style="height: 30vh">    
                <h2 class="text-padding" style="text-align: center; font-family: Courier New">HTTP FILE EXFILTRATOR</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <div class="container">
                  <div class="row">
                    <div class="col-md-3">
                    </br>
                    </div>
                    <div class="col-md-6">
                    
                         <div class="input-group mb-3">
                    	   <input type="file" name="file" id="file" class="form-control">
                   	 </div>
                   	 <div class="text-center">
                    	<input type="submit" value="Upload File" name="submit" class="btn btn-secondary">
                    	</div>
                    	</div>
                    	<div class="col-md-3">
                    	</br>
                    	</div>
                  </div>
                </div>   
                </form>
            </div>
        </div> 
    </div>
</body>
</html>
