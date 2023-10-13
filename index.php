<?php
header('Content-Type: application/json; charset=utf-8');

require 'config/connect.php';
require 'routes/index.php';

$method = $_SERVER['REQUEST_METHOD'];

$url = $_GET['url'];
$params = explode('/', $url);

useRouter($connect, $params, $method);

?>