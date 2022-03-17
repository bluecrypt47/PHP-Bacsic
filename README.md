## Connection MySQL

File `handle.php` dùng để kết nối DB.
```php
<?php
$conn = mysqli_connect('localhost', 'root', '', 'test') or die('Lỗi kết nối');
mysqli_set_charset($conn, "utf8");
?>
```
## Register user

File `register.php` là form register và gọi handle để có thể truy vấn trong DB và dùng Method là POST.
```html, php
 <form method="post" action="register.php" class="form">

        <h2>Register</h2>

        Email: <input type="email" name="email" value="" required />

        Password: <input type="password" name="password" value="" required />

        Username: <input type="text" name="username" value="" required>

        <input type="submit" name="register" value="Register" />
        <a href="./login.php">Login</a>
        <?php require 'handle.php'; ?>
    </form>
```
## Login
File `login.php` cũng là form login và gọi handle để có thể truy vấn trong DB và dùng Method là POST.
```php
<form method="post" action="login.php" class="form">

    <h2>Login</h2>

    Email: <input type="email" name="email" value="" required />

    Password: <input type="password" name="password" value="" required />

    <input type="submit" name="login" value="Login" />
    <a href="./register.php">Register</a>
    <?php require 'handle.php'; ?>
  </form>
```
## Upload file

File `upload.php`, trong trang này, user cần chọn file mà mình muốn `Upload` và upload lên DB. Trong đây, cá dùng tới `enctype="multipart/form-data"` nếu user muốn upload lên được và dùng Method là POST.
```php
<form action="upload.php" class="form" method="POST" enctype="multipart/form-data">
        <h2 class="form-heading">Upload File</h2>
        <div class="form-group">
            <label for="InputFile">File input</label>
            <input type="file" name="file" id="InputFile">

        </div>
        <input class="btn btn-lg btn-primary btn-block" type="submit" name="upload" value="Upload" />
        <?php require 'handle.php'; ?>s
    </form>
```
## Download
```php
<?php
            if (isset($_GET['file_id'])) {
                $id = $_GET['file_id'];

                // fetch file to download from database
                $sql = "SELECT * FROM upload WHERE id=$id";
                $result = mysqli_query($conn, $sql);

                $file = mysqli_fetch_assoc($result);
                $filepath = "uploads/" . $file['name'];

                if (file_exists($filepath)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($filepath));
                    flush();
                    readfile($filepath);
                    exit;
                }
            }

            ?>
```
## Search file
Dung `like ` trong DB de tim kiem.
## Comment

```php

```