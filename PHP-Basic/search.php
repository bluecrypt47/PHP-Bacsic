<?php
$conn = mysqli_connect('localhost', 'root', '', 'test');

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
