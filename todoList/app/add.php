<?php
/* this function gets the activity inside the Data bank after making sure
 that the required text (title) is not empty 
 */
if(isset($_POST['title'])){
    require '../db_conn.php';

    $title = $_POST['title'];
    $deadLine = $_POST['deadLine'];
    
    if(empty($title)) {
        header ("Location:../index.php?mess=error_title");}
    else if( empty ($deadLine)) {
        header ("Location:../index.php?mess=error_date");
    } else {
        $stmt=$conn->prepare("INSERT INTO activities(title,dead_line) VALUES (?,?)");
        $res =$stmt->execute(array($title,$deadLine));       
        if($res) {
            header("Location:../index.php?mess=success");
        } else {
            header("Location:../index.php");
        }
        $conn= null;
        exit();
    }
}
