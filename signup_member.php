<?php
require_once __DIR__ . "/config/db.php";

$full_name = "";
$preferred_name = "";
$roll = "";
$email = "";
$phone = "";
$dept = "";
$semester = "";
$password = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name'] ?? "");
    $preferred_name = trim($_POST['preferred_name'] ?? "");
    $roll = trim($_POST['roll'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $phone = trim($_POST['phone'] ?? "");
    $dept = trim($_POST['dept'] ?? "");
    $semester = trim($_POST['semester'] ?? "");
    $password = $_POST['password'] ?? "";

    if (
        $full_name != "" &&
        $preferred_name != "" &&
        $roll != "" &&
        $email != "" &&
        $phone != "" &&
        $dept != "" &&
        $semester != "" &&
        $password != ""
    ) {

        // 🔥 FIX: HASH PASSWORD
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // INSERT
        $stmt = mysqli_prepare(
            $connection,
            "INSERT INTO kds_db.member_db
            (full_name, preferred_name, roll, email, phone, dept, semester, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssss",
            $full_name,
            $preferred_name,
            $roll,
            $email,
            $phone,
            $dept,
            $semester,
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

    Roll:<br>
    <input type="text" name="roll"><br><br>

    Email:<br>
    <input type="email" name="email"><br><br>

    Phone:<br>
    <input type="text" name="phone"><br><br>

    Dept:<br>
    <input type="text" name="dept"><br><br>

    Semester:<br>
    <input type="text" name="semester"><br><br>

    Password:<br>
    <input type="password" name="password"><br><br>

    <button type="submit">Submit</button>
</form>

</body>
</html>