<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

$id = (int) $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM resources WHERE id=$id");
$res = mysqli_fetch_assoc($result);

if (!$res) {
    echo "Resource not found";
    exit;
}

if (isset($_POST['update'])) {

    $title = $_POST['title'];
    $link = $_POST['link'];

    mysqli_query($conn, "
        UPDATE resources
        SET title='$title', link='$link'
        WHERE id=$id
    ");

    header("Location: index.php?page=profile_admin");
    exit;
}
?>

<h2>Edit Resource</h2>

<form method="POST">
    <input type="text" name="title" value="<?= $res['title'] ?>"><br><br>
    <input type="text" name="link" value="<?= $res['link'] ?>"><br><br>

    <button name="update">Update</button>
</form>