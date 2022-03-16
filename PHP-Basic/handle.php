<?php
header('Content-Type: text/html; charset=utf-8');
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'test') or die('Connection fail!');
mysqli_set_charset($conn, "utf8");

// Dùng isset để kiểm tra Form
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = md5(addslashes(trim($_POST['password'])));
    $email = trim($_POST['email']);


    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Two password do not match");
    }

    // Kiểm tra username hoặc email có bị trùng hay không
    $sql = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";

    // Thực thi câu truy vấn
    $result = mysqli_query($conn, $sql);

    // Nếu kết quả trả về lớn hơn 1 thì nghĩa là username hoặc email đã tồn tại trong CSDL
    if (mysqli_num_rows($result) > 0) {
        echo '<script language="javascript">alert("Email or username has existed!"); window.location="register.php";</script>';
        // Dừng chương trình
        die();
    } else {
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username','$password','$email')";
        echo '<script language="javascript">alert("Register Successfully!"); window.location="register.php";</script>';

        if (mysqli_query($conn, $sql)) {
            echo "Tên đăng nhập: " . $_POST['username'] . "<br/>";
            echo "Mật khẩu: " . $_POST['password'] . "<br/>";
            echo "Email đăng nhập: " . $_POST['email'] . "<br/>";
        } else {
            echo '<script language="javascript">alert("Register Fail!"); window.location="login.php";</script>';
        }
    }
}

// bắt đầu session
session_start();
// Login
if (isset($_POST['login'])) {

    $email = addslashes(trim($_POST['email']));
    $password = md5(addslashes(trim($_POST['password'])));


    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Two password do not match");
    }

    // Kiểm tra email và password có trong DB không
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";

    // Thực thi câu truy vấn
    $result = mysqli_query($conn, $sql);

    // Nếu kết quả trả về lớn hơn 1 thì nghĩa là username hoặc email đã tồn tại trong CSDL
    if (mysqli_num_rows($result) > 0) {

        echo '<script language="javascript">alert("Login Successfully!"); window.location="home.php";</script>';
    } else {
        echo '<script language="javascript">alert("Email has existed!"); window.location="login.php";</script>';
        die();
    }
    $_SESSION['email'] = $email;
    echo "Xin chào " . $username;
    die();
}

//Upload file
if (isset($_POST['upload'])) {

    // lay thong tin file upload
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $email = $_SESSION['email'];
    $download = $_FILES['file']['download'];
    $destination = './uploads/' . $name;

    $extension = pathinfo($name, PATHINFO_EXTENSION);

    $file = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    if (!in_array($extension, ['zip', 'pdf', 'png', 'jpg', 'jpeg', 'docx', 'gif'])) {
        echo "File tail must be zip, pdf, png, jpg, jpeg, docx or gif";
    } else {
        if (move_uploaded_file($file, $destination)) {
            $sql = "INSERT INTO upload (name, size, email) VALUES ('$name',  $size, '$email')";

            if (mysqli_query($conn, $sql)) {
                echo '<script language="javascript">alert("Upload file Successfully!"); window.location="home.php";</script>';
            } else {
                echo '<script language="javascript">alert("Upload file Fail!"); window.location="upload.php";</script>';
                die();
            }
        }
    }
}

// Download
if (isset($_GET['file_id'])) {
    $id = $_GET['file_id'];

    // fetch file to download from database
    $sql = "SELECT * FROM upload WHERE id=$id";
    $result = mysqli_query($conn, $sql);

    $file = mysqli_fetch_assoc($result);
    $filepath = "uploads/" . $file['name'];

    if (file_exists($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('uploads/' . $file['name']));
        readfile('uploads/' . $file['name']);

        $newCount = $file['download'] + 1;
        $updateQuery = "UPDATE upload SET download=$newCount WHERE id =$id";
        mysqli_query($conn, $updateQuery);
        exit;
    }
}
