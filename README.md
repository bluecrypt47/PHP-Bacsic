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
Với `Search` tôi sẽ dùng `like` trong sql để có thể tìm ra file có tên gần giống với từ khóa tìm kiếm nhất.
```php
// Nếu người dùng submit form thì thực hiện
if (isset($_REQUEST['ok'])) {
    // Gán hàm addslashes để chống sql injection
    $search = addslashes($_GET['search']);

    // Nếu $search rỗng thì báo lỗi, tức là người dùng chưa nhập liệu mà đã nhấn submit.
    if (empty($search)) {
        echo "Enter the data you want to search";
    } else {
        // Dùng câu lênh like trong sql và sứ dụng toán tử % của php để tìm kiếm dữ liệu chính xác hơn.
        $query = "select * from upload where name like '%$search%'";

        // Thực thi câu truy vấn
        $sql = mysqli_query($conn, $query);

        // Đếm số đong trả về trong sql.
        $num = mysqli_num_rows($sql);

        // Nếu có kết quả thì hiển thị, ngược lại thì thông báo không tìm thấy kết quả
        if ($num > 0 && $search != "") {
            // Dùng $num để đếm số dòng trả về.
            echo "$num results returned with <b>$search</b>";

            // Vòng lặp while & mysql_fetch_assoc dùng để lấy toàn bộ dữ liệu có trong table và trả về dữ liệu ở dạng array.
            echo '<table border="1" cellspacing="0" cellpadding="10" align="center">';
            while ($row = mysqli_fetch_assoc($sql)) {
                echo '<tr>';
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['email']}</td>";
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "No results were found!";
        }
    }
}
```
## Comment
Ở đây, user sẽ là comment từng file được upload lên. Nếu chưa login thì sẽ phải login để có thể comment.
```php
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
```