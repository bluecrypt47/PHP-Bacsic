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
    $email = $_SESSION['email'];

    if (empty($name) && empty($size) && empty($type)) {
        array_push($errors, "File not found!");
    }

    if (isset($name) && !empty($name)) {
        $sql = "INSERT INTO upload (name, email) VALUES ('$name', '$email')";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<script language="javascript">alert("File has existed!"); window.location="upload.php";</script>';
        } else {
            echo '<script language="javascript">alert("Upload file Successfully!"); window.location="home.php";</script>';
            die();
        }
    } else {
        echo '<script language="javascript">alert("Chose file upload!"); window.location="upload.php";</script>';
    }
}
