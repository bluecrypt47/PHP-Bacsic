<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #eee;
        }

        .form {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }

        .form .form-heading,
        .form .checkbox {
            margin-bottom: 10px;
        }

        .form .checkbox {
            font-weight: normal;
        }

        .form .form-control {
            position: relative;
            height: auto;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            padding: 10px;
            font-size: 16px;
        }

        .form .form-control:focus {
            z-index: 2;
        }

        .form input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="styles.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <a class="btn btn-lg  " href="index.php"><b>Home</b></a>
    <form action="upload.php" class="form" method="POST" enctype="multipart/form-data">
        <h2 class="form-heading">Upload File</h2>
        <div class="form-group">
            <label for="InputFile">File input</label>
            <input type="file" name="file" id="InputFile">
            <!-- <p class="help-block">Upload Files with Capacity <= 100MB</p> -->

        </div>
        <input class="btn btn-lg btn-primary btn-block" type="submit" name="upload" value="Upload" />
        <?php require 'handle.php'; ?>
    </form>
</body>

</html>