<?php
$dsn = "mysql:host=localhost;dbname=libri_db;charset=utf8";
$user = "root";
$password = "";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode(['status'=>'error','message'=>$e->getMessage()]));
}
?>