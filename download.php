<?php 
    require 'driver/config.php';
    if(isset($_GET['download'])){
$sql="SELECT * from book where b_id=?";
$book3=$pdo->prepare($sql);
$book3->bindValue(1,$_GET['download']);
$book3->execute();
$data = $book3->fetch();

    $dir = $data['b_src'];
    $zip_file = $data['b_name'].'.zip';
    header("Content-type: application/zip"); 
    header("Content-Disposition: attachment; filename=$zip_file");
    header("Content-length: " . filesize($dir));
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    readfile("$dir");
    // header("location:$dir;");

    }
?>
