<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['full_name'];
    $roll = $_POST['roll'];
    $dept = $_POST['department'];
    $email = $_POST['email'];
    $question = $_POST['question'];

    mysqli_query($conn, "
        INSERT INTO queries(full_name, roll, department, email, question)
        VALUES('$name','$roll','$dept','$email','$question')
    ");
    $stmt = $conn->prepare("
    INSERT INTO queries(full_name, roll, department, email, question, is_read)
    VALUES(?,?,?,?,?,0)
");

    header("Location: index.php?page=home&msg=sent");
    exit;
}
?>