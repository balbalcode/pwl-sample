<?php

$host    = 'localhost';
$db_name = 'tik_pbl';
$user    = 'root';
$pass    = '';

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
