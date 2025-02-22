<?php
// เชื่อมต่อฐานข้อมูล (Connect to the database)
$servername = "localhost";
$username = "its66040233111";
$password = "L1upP4Q8";
$dbname =  "its66040233111";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่าคำค้นหาจากฟอร์ม (Get search value from form)
$search = isset($_POST['search']) ? $_POST['search'] : '';

// สร้าง SQL query สำหรับค้นหาข้อมูล (Create SQL query to search data)
$sql = "SELECT * FROM CatBreeds WHERE (name_th LIKE '%$search%' OR name_en LIKE '%$search%') AND is_visible = 1";
$result = $conn->query($sql);

$conn->close();
?>

<?php
session_start();

// ตรวจสอบว่ามีการเข้าสู่ระบบหรือยัง
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงข้อมูลสายพันธุ์แมว</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            margin-bottom: 30px;
        }
        .cat-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); /* ขยายขนาดช่องให้กว้างขึ้น */
            gap: 20px;
        }
        .cat-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 550px; /* เพิ่มความสูงของช่อง */
            overflow: hidden;
        }
        .cat-card img {
            width: 100%;
            height: 250px; /* เพิ่มขนาดรูปภาพให้ใหญ่ขึ้น */
            object-fit: cover;
            border-radius: 8px;
        }
        .cat-card .content {
            flex-grow: 1;
            overflow-y: auto;
            max-height: 200px; /* ขยายความสูงของเนื้อหา */
            padding-right: 10px;
        }
        .cat-card a {
            display: block;
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
            color: #007bff;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .search-box {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="admin.php">Home Admin</a></li>
                    <li><a href="add_cat.php">Add Cat</a></li>
                    <li><a href="visible.php">Edit</a></li>
                    <li><a href="imageList.php" target="_blank">IMG</a></li>
                    <li><a href="logout.php">ออกจากระบบ</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2>สายพันธุ์แมวยอดนิยม</h2>
        
        <form method="POST" action="">
            <div class="search-box">
                <input type="text" class="form-control" name="search" placeholder="ค้นหาสายพันธุ์แมว..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
        </form>

        <div class="cat-container">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='cat-card'>";
                    echo "<h3>" . $row['name_th'] . " (" . $row['name_en'] . ")</h3>";
                    echo "<img src='" . $row['image_url'] . "' alt='Image'>";
                    echo "<div class='content'>";
                    echo "<p><strong>คำอธิบาย:</strong> " . $row['description'] . "</p>";
                    echo "<p><strong>ลักษณะทั่วไป:</strong> " . $row['characteristics'] . "</p>";
                    echo "<p><strong>คำแนะนำการเลี้ยงดู:</strong> " . $row['care_instructions'] . "</p>";
                    echo "</div>";
                    echo "<a href='edit_cat.php?id=" . $row['id'] . "'>แก้ไข (Edit)</a> | ";
                    echo "<a href='delete_cat.php?id=" . $row['id'] . "'>ลบ (Delete)</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>ไม่มีข้อมูลแสดง</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>
