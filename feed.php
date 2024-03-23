<?php
include_once 'dbConnection.php';
$ref = @$_GET['q'];
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$id = uniqid();
$date = date("Y-m-d");
$time = date("h:i:sa");
$feedback = $_POST['feedback'];
$ease = $_POST['ease'];
$content = $_POST['content'];
$feedbackk = $_POST['feedbackk'];
$improvement = $_POST['improvement'];
$q = mysqli_query($con, "INSERT INTO feedback (id, name, username, subject, feedback, ease, content, feedbackk, improvement, date, time) VALUES ('$id', '$name', '$email', '$subject', '$feedback', '$ease', '$content', '$feedbackk', '$improvement', '$date', '$time')") or die("Error: " . mysqli_error($con));
header("location:$ref?q=Thank you for your valuable feedback");
?>
