### Người thực hiện: Lê Trần Văn Chương
Ngày làm: 14 - 18/03/2022.

Mục lục:
- [Các hàm sử dụng](#các-hàm-sử-dụng)
- [Connection MySQL](#connection-mysql)
- [Register user](#register-user)
- [Login](#login)
- [Upload file](#upload-file)
- [Download](#download)
- [Search file](#search-file)
- [Comment](#comment)

## Các hàm sử dụng
Hàm `isset` dùng để kiểm tra 'register' đã được khởi tạo chưa. 

Hàm `trim` để lấy chuỗi và bỏ khoảng chắn đầu cuối.

Hàm  `addslashes` dùng để chèn dấu gạch chéo "\" vào trước dấu nháy đôi, đơn, dấu gạch chéo ngược và NUL (nhằm xử lý các ký tự đặc biệt có thể gây ra các vấn đề bảo mật).

Hàm `md5` dùng để mã hóa md5 cho chuỗi truyền vào.

Hàm `empty` dùng để kiểm tra chuỗi rỗng.

Hàm `mysqli_connect()` sẽ kết nối tới MySQL server. Cú pháp: `mysqli_connect( $host, $username, $pass, $dbname);`. Trong đó: 
- `$host` là tên hosting.
- `$username` là tên người dùng MySQL.
- `$pass` là mật khẩu của người dùng.
- `$dbname` là tên cơ sở dữ liệu cần kết nối.

Hàm `mysqli_query()` dùng để thực thi các câu truy vấn với DB:
- Cú pháp: `mysqli_query( $connect, $sql, $mode);` Trong đó:
    - `$connect` là kết nối MySQL.
    - `$sql` là câu truy vấn.
    - `$mode` là tham số tùy chọn, mang một trong các giá trị sau:
        - `MYSQLI_USE_RESULT` : sử dụng khi cần lấy một lượng lớn dữ liệu.
        - `MYSQLI_STORE_RESULT` : giá trị mặc định nếu không truyền.

Hàm `mysqli_num_rows()` sẽ trả về số hàng trong tập hợp kết quả truyền vào có cú pháp: `mysqli_num_rows( $result);`. Trong đó: `$result` là tập hợp kết quả trả về từ các hàm `mysqli_query()`, `mysqli_store_result()` hoặc `mysqli_use_result()`. 

Hàm `mysqli_fetch_assoc()` sẽ tìm và trả về một dòng kết quả của một truy vấn MySQL nào đó dưới dạng một mảng kết hợp. Cú pháp: `mysqli_fetch_assoc( $result);`. Trong đó: `$result` là kết quả của truy vấn, là kết quả trả về của các hàm: `mysqli_query()`, `mysqli_store_result()` hoặc `mysqli_use_result()`.

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
```php
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
        echo '<script language="javascript">alert("Register Successfully!"); window.location="login.php";</script>';

        if (mysqli_query($conn, $sql)) {
            echo "Tên đăng nhập: " . $_POST['username'] . "<br/>";
            echo "Mật khẩu: " . $_POST['password'] . "<br/>";
            echo "Email đăng nhập: " . $_POST['email'] . "<br/>";
        } else {
            echo '<script language="javascript">alert("Register Fail!"); window.location="register.php";</script>';
        }
    }
}
```
## Login

File `login.php` cũng là form login và gọi handle để có thể truy vấn trong DB và dùng Method là POST.
```php
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

        echo '<script language="javascript">alert("Login Successfully!"); window.location="index.php";</script>';
    } else {
        echo '<script language="javascript">alert("Email has existed!"); window.location="login.php";</script>';
        die();
    }
    $_SESSION['email'] = $email;
    echo "Xin chào " . $username;
    die();
}
```
## Upload file

File `upload.php`, trong trang này, user cần chọn file mà mình muốn `Upload` và upload lên DB. Trong đây, cá dùng tới `enctype="multipart/form-data"` nếu user muốn upload lên được và dùng Method là POST.
```php
if (isset($_POST['upload'])) {

    // lay thong tin file upload
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $email = $_SESSION['email'];
    $destination = './uploads/' . $name;

    $extension = pathinfo($name, PATHINFO_EXTENSION);

    $file = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    if (!in_array($extension, ['pdf', 'png', 'jpg', 'jpeg', 'gif'])) {
        echo "File tail must be pdf, png, jpg, jpeg or gif";
    } else {
        if (move_uploaded_file($file, $destination)) {
            $sql = "INSERT INTO upload (name, size, email) VALUES ('$name',  $size, '$email')";

            if (mysqli_query($conn, $sql)) {
                echo '<script language="javascript">alert("Upload file Successfully!"); window.location="index.php";</script>';
            } else {
                echo '<script language="javascript">alert("Upload file Fail!"); window.location="upload.php";</script>';
                die();
            }
        }
    }
}
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
