<?php
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";


$con = mysqli_connect($servername, $username, $password, $db_name);
if (isset($_POST['id'])){
    $id = $_POST['id'];
    $query=mysqli_query($con,"delete from register where id='$id'");
}
?>