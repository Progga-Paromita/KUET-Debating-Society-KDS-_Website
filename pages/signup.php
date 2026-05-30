<?php
$role = $_GET['role'] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($role == "admin") {

        // 🔴 send request instead of direct signup
        $sql = "INSERT INTO admin_requests(name,email,password) 
                VALUES('$name','$email','$password')";
        mysqli_query($conn, $sql);

        echo "Admin request sent. Wait for approval.";

    } else {

        $sql = "INSERT INTO member(name,email,password) 
                VALUES('$name','$email','$password')";
        mysqli_query($conn, $sql);

        echo "Member registered successfully!";
    }
}
?>

<h2>Signup as <?= $role ?></h2>

<form method="POST">
    <input name="name" placeholder="Name"><br>
    <input name="email" placeholder="Email"><br>
    <input name="password" type="password" placeholder="Password"><br>
    <button>Signup</button>
</form>