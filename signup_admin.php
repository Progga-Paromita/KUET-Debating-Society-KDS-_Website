<?php
require_once __DIR__ . "/config/db.php";

$full_name = "";
$preferred_name = "";
$role = "";
$email = "";
$phone = "";
$password = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name'] ?? "");
    $preferred_name = trim($_POST['preferred_name'] ?? "");
    $role = trim($_POST['role'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $phone = trim($_POST['phone'] ?? "");
    $password = $_POST['password'] ?? "";

    if (
        $full_name != "" &&
        $preferred_name != "" &&
        $role != "" &&
        $email != "" &&
        $phone != "" &&
        $password != ""
    ) {

        // 🔥 FIX: HASH PASSWORD
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // INSERT
        $stmt = mysqli_prepare(
            $connection,
            "INSERT INTO kds_db.admin_db
            (full_name, preferred_name, role, email, phone, password)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $full_name,
            $preferred_name,
            $role,
            $email,
            $phone,
            $hashed_password
        );

        if (mysqli_stmt_execute($stmt)) {
            $success = "✅ Data inserted successfully!";
        } else {
            $success = "❌ Error: " . mysqli_error($connection);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Form</title>
</head>
<body>

<h2>Enter Your Info</h2>

<?php if ($success != ""): ?>
    <p><?php echo $success; ?></p>
<?php endif; ?>

<form method="POST">
    Full Name:<br>
    <input type="text" name="full_name"><br><br>

    Preferred Name:<br>
    <input type="text" name="preferred_name"><br><br>

    Role:<br>
    <input type="text" name="role"><br><br>

    Email:<br>
    <input type="email" name="email"><br><br>

    Phone:<br>
    <input type="text" name="phone"><br><br>

    Password:<br>
    <input type="password" name="password"><br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>