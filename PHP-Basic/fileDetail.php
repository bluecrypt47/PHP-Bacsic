<?php
$conn = mysqli_connect('localhost', 'root', '', 'test');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Detail</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">

    <link rel="stylesheet" href="styles.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        table {
            width: 60%;
            border-collapse: collapse;
            margin: 100px auto;
        }

        th,
        td {
            height: 50px;
            vertical-align: center;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_GET['file_id'])) {
        $id = $_GET['file_id'];

        // fetch file to download from database
        $sql = "SELECT * FROM upload WHERE id=$id";
        $result = mysqli_query($conn, $sql);

        $files = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    ?>
    <h2 align="center"> <b>Details</b> </h2>
    <a class="btn btn-lg  " href="index.php"><b>Home</b></a>
    <table>
        <thead>
            <th>Filename</th>
            <th>Size(MB)</th>
            <th>Author</th>
        </thead>
        <tbody>
            <?php foreach ($files as $file) : ?>
                <tr>
                    <td><?php echo $file['name']; ?></td>
                    <td><?php echo $file['size'] / 1000 . "KB"; ?></td>
                    <td><?php echo $file['email']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2> Comment </h2>
    <form method="POST">
        <textarea name="content" style="width: 100%;" rows="5" placeholder="Write comment here..."></textarea>
        <input type="submit" value="Send" name="submit" class="btn btn-primary">
    </form>

    <?php
    if (isset($_POST['content'])) {
        $content = $_POST['content'];
        $idFile = $_GET['file_id'];

        if (empty($content)) {
            echo '<script language="javascript">alert("Enter comment, pls!"); window.location="fileDetail.php";</script>';
        } elseif ($idFile == 0) {
            echo '<script language="javascript">alert("You have not commented!"); window.location="fileDetail.php";</script>';
        } elseif (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];

            $sql = "INSERT INTO comments (email, idFile, content) VALUES ('$email','$idFile','$content')";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                $result = mysqli_error($conn);
            }
        }
    }
    ?>
    <?php
    $id = $_GET['file_id'];

    $sql = "SELECT * FROM comments where idFile = $id";
    $result = mysqli_query($conn, $sql);

    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    ?>
    <table>
        <thead>
            <th>Email</th>
            <th>Content</th>
            <th>Time</th>
        </thead>
        <tbody>
            <?php foreach ($comments as $comment) : ?>
                <tr>
                    <td><?php echo $comment['email']; ?></td>
                    <td><?php echo $comment['content']; ?></td>
                    <td><?php echo $comment['createDate']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>