<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'userform');

$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$sql="DELETE FROM usertable WHERE email='gawrishankard04@gmail.com'";

$result=mysqli_query($con,$sql);
if($result){
    echo "Record deleted successfully";
}else{
    echo "Error deleting record: " . mysqli_error($con);
}
?>