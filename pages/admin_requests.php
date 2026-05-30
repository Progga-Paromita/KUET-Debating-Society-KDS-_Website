<?php
// only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
    header("Location: index.php?page=login");
    exit;
}

/* =========================
   APPROVE REQUEST
========================= */
if (isset($_GET['approve'])) {

    $id = $_GET['approve'];

    $req = mysqli_query($conn, "SELECT * FROM admin_requests WHERE id=$id");
    $data = mysqli_fetch_assoc($req);

    // move to admin table
    mysqli_query($conn, "INSERT INTO admin(name,email,password)
        VALUES('{$data['name']}','{$data['email']}','{$data['password']}')");

    // mark as approved
    mysqli_query($conn, "UPDATE admin_requests SET status='approved' WHERE id=$id");

    echo "Admin approved!";
}

/* =========================
   REJECT REQUEST
========================= */
if (isset($_GET['reject'])) {

    $id = $_GET['reject'];

    // mark as rejected (you can also delete if you want)
    mysqli_query($conn, "UPDATE admin_requests SET status='rejected' WHERE id=$id");

    echo "Admin request rejected!";
}
?>

<h2>Pending Admin Requests</h2>

<?php
$result = mysqli_query($conn, "SELECT * FROM admin_requests WHERE status='pending'");

while ($row = mysqli_fetch_assoc($result)) {
    echo "<p>
        <b>{$row['name']}</b> - {$row['email']}
        
        <a href='index.php?page=admin_requests&approve={$row['id']}'
           style='color:green; margin-left:10px;'>Approve</a>

        <a href='index.php?page=admin_requests&reject={$row['id']}'
           style='color:red; margin-left:10px;'>Reject</a>
    </p>";
}
?>