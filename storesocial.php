<?php
$conn = new mysqli("localhost", "root", "", "eventstore");

$name = $_POST['name'];
$category = $_POST['category'];
$date = $_POST['date'];
$desc = $_POST['description'];
$place = $_POST['place'];
$link = $_POST['link'];

$imagePath = '' . basename($_FILES['image']['name']);
move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

$query = "INSERT INTO social (name, category, date, description, place, image, link)
          VALUES ('$name', '$category', '$date', '$desc', '$place', '$imagePath', '$link')";

$conn->query($query);
header("Location: social1.php");
?>
